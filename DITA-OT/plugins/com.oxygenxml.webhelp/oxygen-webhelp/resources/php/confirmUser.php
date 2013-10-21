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

	$toReturn= new JsonResponse();
if (isset($_POST['id']) && trim($_POST['id']) != ''){
	$realId= base64_decode($_POST['id']);
	//list($id,$date,$action,$newPassword) = explode("|", $realId);
	$args = explode("|", $realId);
	$id=$args[0];
	$date=$args[1];
	$action="new";
	$newPassword="";
	if (count($args)>2){
		$action=$args[2];
		$newPassword=$args[3];
	}

	$user= new User($dbConnectionInfo);
	//echo "id=".$id." date=".$date;
	
	$currentDate= date("Y-m-d G:i:s");
	$days = Utils::getTimeDifference($currentDate, $date, 3);
	if ($days>7){
		$toReturn->set("error", true);
		$toReturn->set("msg", "Confirmation code expired!");				
	}else{
		$productTranslate=(defined("__PRODUCT_NAME__") ? __PRODUCT_NAME__ : $_POST['productName']);
		if ($action=="recover"){
			$email=$id;
			$userName = $user->changePassword($email, $newPassword);
			if ($userName!=""){
				$template = new Template("./templates/recover.html");
				$confirmationMsg = $template->replace(
						array("username"=>$userName,
									"password"=>$newPassword,
									"productName"=>$productTranslate));
				//
// 				$confirmationMsg = "Your generated password form username '".$userName."' is '".$newPassword."'";
// 				$confirmationMsg.="<br/>Thank you !";
				$mail = new Mail();
				$mail->Subject("[".$productTranslate."] ".$translate['RecoveredEmailSubject']);
				$mail->To($email);
				$mail->From(__EMAIL__);
				$mail->Body($confirmationMsg);
				$mail->Send();
				$toReturn->set("error", false);
				$toReturn->set("msg", Utils::translate('signUp.confirmOk'));
			}else{
				$toReturn->set("error", true);
				$toReturn->set("msg", Utils::translate("signUp.invalidPswd"));				
			}
		}else{
// 			echo print_r($_SESSION,false);
			if ($user->confirmUser($id)){
				$pName=(isset($_POST['productName']) ? $_POST['productName'] :"");
				$pVersion=(isset($_POST['productVersion']) ? $_POST['productVersion'] : "");
				$fullUser=base64_encode($pName."_".$pVersion."_user");				
				$ses=Session::getInstance();			
				$ses->$fullUser=$user;
// 				echo print_r($_SESSION,false);
// 				echo $user->msg;
				$toReturn->set("error", false);
				$toReturn->set("msg", $user->msg);
			}else{
				$toReturn->set("error", true);
				$toReturn->set("msg", $user->msg);				
			}
		}		
	}
}else{
	$toReturn->set("error", true);
	$toReturn->set("msg", "Invalid data!");	
}
echo $toReturn;

?>