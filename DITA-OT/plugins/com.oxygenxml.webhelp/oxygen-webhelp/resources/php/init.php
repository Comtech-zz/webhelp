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
function isDebug(){
	return defined('__DEBUG__') && __DEBUG__;
}

function getUpDir($level){
	$i=1;
	$toReturn=dirname(__FILE__);
	while ($i<$level){
		$i++;
		$toReturn=dirname($toReturn);
	}
	return $toReturn;
}

	$baseDir = dirname(dirname(__FILE__));

	define("__BASE_DIR__", getUpDir(4));
	
	if (file_exists($baseDir.'/php/config/config.php')){
  	include_once $baseDir.'/php/config/config.php';
  	$ses=Session::getInstance();
  	if (!isset($ses->errBag)){
  		$ses->errBag= new OxyBagHandler();
  	}  	  	
	}else{
		error_log("warn couldn't find ".$baseDir.'/php/config/config.php');
	}
	
	global $dbConnectionInfo;
	
	if (file_exists($baseDir.'/localization/strings.php')){
  	include_once $baseDir.'/localization/strings.php';
	}

//set error handler
set_error_handler("OxyHandler::error");
set_exception_handler("OxyHandler::exception");


// if (!isset($dbConnectionInfo)){
// 	throw new Exception("DB connection info not available!");
// }


/**
 * Loads a class form a specified directory 
 * @param String $dirToCheck directory to cheeck for class
 * @param String $fileName class to load
 * @return boolean
 */
function loadClassFromDir($dirToCheck,$fileName){
	$toReturn=FALSE;
	if ($handle = opendir($dirToCheck)) {
		/* recurse through directory. */
		while (false !== ($directory = readdir($handle)) && !$toReturn) {
			if (is_dir($dirToCheck.$directory)){
				if ($directory!="." && $directory!=".."){
					$path = trim($dirToCheck.$directory."/".$fileName.".php");
					if(file_exists($path)){
						require_once $path;						
						$toReturn=TRUE;											
					}
					if (!$toReturn){						
						$toReturn=loadClassFromDir($dirToCheck.$directory."/",$fileName);
					}
				}
			}
		}
		closedir($handle);
	}
	if (isDebug()){
		echo 'File : '.$fileName;
		if ($toReturn){
			echo " found in ";
		}else{
			echo " not found in ";
		}
		echo $dirToCheck."<br/>";
	}
	return $toReturn;
}

/**
 * @param String $name
 * @throws Exception
 */
function __autoload($name) {
	$found=FALSE;
	if (isDebug()){
		echo "Document Root:".$_SERVER['DOCUMENT_ROOT']."<br/>";
		if (defined('__BASE_URL__')){
			echo "Base URL:".__BASE_URL__."<br/>";
		}
	}
	$baseDir = dirname(dirname(__FILE__));
		
	
	if (isDebug()){ echo 'dir:'.$baseDir."<br/>";}
// 	if (defined('__BASE_URL__')){
// 		$parts=explode("/", __BASE_URL__,4);
// 	 if (count($parts)<4){
// 		  $classPath = $_SERVER['DOCUMENT_ROOT']."/oxygen-webhelp/resources/php/classes/";
//     }else{
//       $classPath = $_SERVER['DOCUMENT_ROOT']."/".$parts[3]."/oxygen-webhelp/resources/php/classes/";
//     }
// 	}else{		
		$classPath = $baseDir."/php/classes/";
// 	}
	if (isDebug()){
		echo 'classPath:'.$classPath."<br/>";
	}
	
	$directory=$classPath;
	$path = $classPath.$name.".php";
	if(file_exists($path)){
		require_once $path;		
		$found =TRUE;		
	}else{
		$found = loadClassFromDir($classPath,$name);
	}
	
	if (!$found){
		echo "Can not load $name from $classPath"."<br/>\n";
		throw new Exception("Unable to load $name.");
	}
}

?>