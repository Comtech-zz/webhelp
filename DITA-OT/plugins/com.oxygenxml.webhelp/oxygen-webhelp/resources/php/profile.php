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

//$ses=Session::getInstance();
//echo "test";
// echo print_r($_POST,true);

if (isset($_POST['update']) && trim($_POST['update']) != ''){
		$toReturn=new JsonResponse();
		$toReturn->set("updated", "false");				
	$info=array();

	if (isset($_POST['password']) && trim($_POST['password'])!=""){
		$info['password']=$_POST['password'];
	}
	if (isset($_POST['oldPassword'])){
		$info['oldPassword']= $_POST['oldPassword'];
	}
		$info['name']= $_POST['name'];
		$info['email']= $_POST['email'];
		$info['notifyPage']= $_POST['notifyPage'];
		$info['notifyAll']= $_POST['notifyAll'];
		$info['notifyReply']= $_POST['notifyReply'];
		
	$pName=(isset($_POST['product']) ? $_POST['product'] :"");
	$pVersion=(isset($_POST['version']) ? $_POST['version'] : "");
	$fullUser=base64_encode($pName."_".$pVersion."_user");
	$ses= Session::getInstance();
	if (isset($ses->$fullUser)){
		if ((isset($_POST['userId']) && strlen(trim($_POST['userId']))!="")&&($ses->$fullUser->level='admin')){
			// from admininstrative pages requests
			$info['editByAdmin']= true;
			$info['company']= $_POST['company'];
			$info['level']= $_POST['level'];
			$info['status']= $_POST['status'];
			$info['userId']= $_POST['userId'];				
		}else{
			$info['editByAdmin']=false;
		}
		$err=$ses->$fullUser->updateProfile($info);
		if ($err!=""){
			$toReturn->set("msgType", "info");
			$toReturn->set("msg", $err);
			echo $toReturn;
		}else{
			$toReturn->set("updated", "true");
			echo $toReturn;
		}
	}else{
		$toReturn->set("msgClass", "error");
		$toReturn->set("msg", Utils::translate("err.notLoggedIn"));
		echo $toReturn;
	}

}else if (isset($_POST['select']) && trim($_POST['select']) != ''){
	$toReturn=new JsonResponse();
	$pName=(isset($_POST['product']) ? $_POST['product'] :"");
	$pVersion=(isset($_POST['version']) ? $_POST['version'] : "");
	$fullUser=base64_encode($pName."_".$pVersion."_user");
	$ses= Session::getInstance();
	if (isset($ses->$fullUser)){
		$delim=$_POST['delimiter'];
		$user=$ses->$fullUser;
		$toReturn->set("isLogged", "true");
		$toReturn->set("name", $user->name);
		$toReturn->set("email", $user->email);
		$toReturn->set("notifyPage",$user->notifyPage);
		$toReturn->set("notifyReply",$user->notifyReply);
		$toReturn->set("notifyAll",$user->notifyAll);		
	}else{
		$toReturn->set("isLogged", "false");
	}
	echo $toReturn;
}else{
	echo "Invalid data!";
}
?>
