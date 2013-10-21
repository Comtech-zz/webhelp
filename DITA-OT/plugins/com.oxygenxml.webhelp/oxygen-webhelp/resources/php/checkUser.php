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
require_once "init.php";
$ses=Session::getInstance();

$pName=(isset($_POST['productName']) ? $_POST['productName'] :"");
$pVersion=(isset($_POST['productVersion']) ? $_POST['productVersion'] : "");
$fullUser=base64_encode($pName."_".$pVersion."_user");

if (isset($_POST['userName']) && trim($_POST['userName']) != ''){
	$username= $_POST['userName'];
	$password= $_POST['password'];
	$toReturn=new JsonResponse();
	$toReturn->set("authenticated", "false");	
	
	$user= new User($dbConnectionInfo);
	if ($user->validate($username, $password)){
		$toReturn->set("authenticated", "true");		
		$ses->$fullUser=$user;		
		$toReturn->set("name",$ses->$fullUser->name);
		$toReturn->set("userName",$ses->$fullUser->userName);
		$toReturn->set("level",$ses->$fullUser->level);
	}else{
		if (strlen(trim($user->msg)) > 0){		
			$toReturn->set("error", $user->msg);
		}	
	}
	echo $toReturn;
}elseif (isset($_POST['logOff']) && trim($_POST['logOff']) != ''){
	$ses->errBag=null;
	unset($ses->errBag);
	unset($ses->$fullUser);
	// 		echo print_r($_POST,true);
}elseif (isset($_POST['check']) && trim($_POST['check']) != ''){
		$toReturn=new JsonResponse();
		$toReturn->set("isAnonymous", "false");
		$toReturn->set("loggedIn","false");
	if ((defined('__GUEST_POST__') && !__GUEST_POST__) 
			&& (isset($ses->$fullUser) 
			&& $ses->$fullUser->isAnonymous=='true')){		
		unset($ses->$fullUser);	
	}
	
	
	if ((defined('__GUEST_POST__') && __GUEST_POST__) 
			&& (!isset($ses->$fullUser))){
		$user= new User($dbConnectionInfo);
		// user not logged in and guest is allowed to post
		if (!$user->initAnonymous()){
			$toReturn->set("isAnonymous", "false");
			$toReturn->set("loggedIn","false");
			$toReturn->set("msg","1");
			$toReturn->set("msgType","error");
		}else{
			// anonymous must be logged in
			$ses->$fullUser=$user;
			$toReturn->set("isAnonymous", "true");
			$toReturn->set("loggedIn","true");
			//TODO: to prompt with last log or else?
			//$toReturn->set("msg","2");
			//$toReturn->set("msgType","info");
			$toReturn->set("name",$ses->$fullUser->name);
			$toReturn->set("userName",$ses->$fullUser->userName);
			$toReturn->set("level",$ses->$fullUser->level);			
		}
	}else{
		if (isset($ses->$fullUser) &&  $ses->$fullUser instanceof User){			
			$toReturn->set("isAnonymous", $ses->$fullUser->isAnonymous);
			$toReturn->set("loggedIn","true");
			//TODO: to prompt with last log or else?
			//$toReturn->set("msg","3");
			//$toReturn->set("msgType","info");
			$toReturn->set("name",$ses->$fullUser->name);
			$toReturn->set("userName",$ses->$fullUser->userName);
			$toReturn->set("level",$ses->$fullUser->level);
		}else{			
			$toReturn->set("isAnonymous", "false");
			$toReturn->set("loggedIn","false");
			//TODO: to prompt with last log or else?
			//$toReturn->set("msg","4");
			//$toReturn->set("msgType","error");			
		}
	}
	$comts = New Comment($dbConnectionInfo);
	$minVer=$comts->getMinimVersion($pName);
	$toReturn->set("minVisibleVersion",$minVer);
	echo $toReturn;
}else{
	// 	echo "none".print_r($_POST,true);
}
?>