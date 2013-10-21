<?php
/*
 *  The Syncro Soft SRL License
 *
 *  Copyright (c) 1998-2012 Syncro Soft SRL, Romania.  All rights
 *  reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions
 *  are met:
 *
 *  1. Redistribution of source or in binary form is allowed only with
 *  the prior written permission of Syncro Soft SRL.
 *
 *  2. Redistributions of source code must retain the above copyright
 *  notice, this list of conditions and the following disclaimer.
 *
 *  3. Redistributions in binary form must reproduce the above copyright
 *  notice, this list of conditions and the following disclaimer in
 *  the documentation and/or other materials provided with the
 *  distribution.
 *
 *  4. The end-user documentation included with the redistribution,
 *  if any, must include the following acknowledgment:
 *  "This product includes software developed by the
 *  Syncro Soft SRL (http://www.sync.ro/)."
 *  Alternately, this acknowledgment may appear in the software itself,
 *  if and wherever such third-party acknowledgments normally appear.
 *
 *  5. The names "Oxygen" and "Syncro Soft SRL" must
 *  not be used to endorse or promote products derived from this
 *  software without prior written permission. For written
 *  permission, please contact support@oxygenxml.com.
 *
 *  6. Products derived from this software may not be called "Oxygen",
 *  nor may "Oxygen" appear in their name, without prior written
 *  permission of the Syncro Soft SRL.
 *
 *  THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 *  WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 *  OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED.  IN NO EVENT SHALL THE SYNCRO SOFT SRL OR
 *  ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 *  LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 *  USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 *  OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 *  OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 *  SUCH DAMAGE.
 */
require_once 'init.php';

if (isset($_POST['id']) && trim($_POST['id']) != ''){	
	$encoded=$_POST['id'];
	$decoded=base64_decode($encoded);
	list($id,$action)=explode("&", $decoded);
	$fullUser=base64_encode($_POST['productName']."_".$_POST['productVersion']."_user");
	$commentedPage=moderateComment($id,$action,$fullUser);	
	echo __BASE_URL__.$commentedPage;
}else if (isset($_POST['uncodedId']) && trim($_POST['uncodedId']) != ''){
	$fullUser=base64_encode($_POST['product']."_".$_POST['version']."_user");
	$commentedPage=moderateComment(trim($_POST['uncodedId']),trim($_POST['action']),$fullUser);	
	echo __BASE_URL__.$commentedPage;
}else if (isset($_POST['ids']) && trim($_POST['ids']) != ''){
	$fullUser=base64_encode($_POST['product']."_".$_POST['version']."_user");
	$cmt= new Comment($dbConnectionInfo,"",$fullUser);
	$ids=trim($_POST['ids']);
	$return = $cmt->deleteComments($ids);
	echo $return;
}else if (isset($_POST['page']) && trim($_POST['page']) != ''){
	$fullUser=base64_encode($_POST['product']."_".$_POST['version']."_user");
	approveAll(substr($_POST['page'],(strlen(__BASE_URL__))),$fullUser);
	echo $_POST['page'];
}else{
	echo "Invalid data!";
}

function approveAll($page,$fullUser){
	global $dbConnectionInfo;
	
	$cmt= new Comment($dbConnectionInfo,"",$fullUser);
	
	$returnIds = $cmt->approveAll($page);

		// notify users
	$user = new User($dbConnectionInfo);
	foreach ($returnIds as $key => $updatedId){		
		$usersToNotify=$user->getUsersToNotify($page,$updatedId);		
		$cmtInfo = $cmt->getInfo($updatedId);		
		$productTranslate=(defined("__PRODUCT_NAME__") ? __PRODUCT_NAME__ : $cmtInfo['product']);
		foreach ($usersToNotify as $key => $value){
			$template = new Template("./templates/newComment.html");
		
			$confirmationMsg = $template->replace(array(
					"page"=>$page."#".$updatedId,
					"text"=>$cmtInfo['text'],
					"name"=>$cmtInfo['name'],
					"username"=>$cmtInfo['userName']
			));
			$mail = new Mail();
			$subject="[".$productTranslate."] ".Utils::translate('newCommentApproved');
			$subject.=" [".$page."]";
			$mail->Subject($subject);
			$mail->To($value);
			$mail->From(__EMAIL__);
			$mail->Body($confirmationMsg);
			$mail->Send();
			//$toReturn = "\nSEND to ".$value."user email='".$userEmail."'";
		}
	}
}


function moderateComment($id,$action,$fullUser){
	global $dbConnectionInfo;	
	
	$toReturn="";
	$act=false;
	if ($action=="approved"){
		$act=true;
	}
	$cmt= new Comment($dbConnectionInfo,"",$fullUser);
	$return = $cmt->moderate($id,$action);
	
	$toReturn=$return['page'];
	if ($return['page'] !="" && $act && $return['oldState'] =='new'){
		// notify users
		$user = new User($dbConnectionInfo);
		$usersToNotify=$user->getUsersToNotify($toReturn,$id);
				
		$cmtInfo = $cmt->getInfo($id);
		$productTranslate=(defined("__PRODUCT_NAME__") ? __PRODUCT_NAME__ : $cmtInfo['product']);
				$template = new Template("./templates/newComment.html");
				$confirmationMsg = $template->replace(array(
						"page"=>__BASE_URL__.$toReturn."#".$id,
						"text"=>$cmtInfo['text'],
						"user"=>$cmtInfo['name'],
						"productName"=>$productTranslate
				));
		foreach ($usersToNotify as $key => $value){					
				$mail = new Mail();
				$subject="[".$productTranslate."] ".Utils::translate('newCommentApproved');
				$subject.=" [".$toReturn."]";
				
				$mail->Subject($subject);
				$mail->To($value);
				$mail->From(__EMAIL__);
				$mail->Body($confirmationMsg);
				$mail->Send();
				//$toReturn = "\nSEND to ".$value."user email='".$userEmail."'";			
		}
	}
	return $toReturn;	
}
?>