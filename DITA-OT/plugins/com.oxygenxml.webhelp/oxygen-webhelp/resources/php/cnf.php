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

/**
* @deprecated
*/
Header("content-type: application/x-javascript");

$baseDir = dirname(dirname(__FILE__));
require_once $baseDir.'/php/init.php';

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$baseUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
$baseUrl .= isset($_SERVER['SCRIPT_NAME']) ? dirname(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])))) : dirname(dirname(getenv('SCRIPT_NAME')));

$baseUrl =rtrim($baseUrl, '/\\');

$val=array();
if (defined('__BASE_URL__')){
	$parts=explode("/", __BASE_URL__,4);
	if (count($parts)<4){
		$relPath="/";
	}else{    
		$relPath="/".($parts[3]=="" ? "": $parts[3]);
	}
  	echo 'var conf = {"htpath":"'.$relPath.'","baseUrl":"'.__BASE_URL__.'"};';
}else{
	$parts=explode("/", $baseUrl,4);
	if (count($parts)<4){
		$relPath="/";
	}else{
    $relPath="/".($parts[3]=="" ? "" : $parts[3]."/");
		//$relPath="/".$parts[3]."/";
	}
  echo 'var conf = {"htpath":"'.$relPath.'","baseUrl":"'.$baseUrl.'/"};';
}
echo "
function objToString (obj) {
    var str = '';
    for (var p in obj) {
        if (obj.hasOwnProperty(p)) {
            str += p + '::' + obj[p] + '\\n';
        }
    }
    return str;
}
$.ajaxSetup({
  cache	  	: false,
  timeout 	: 60000,
  error 	: function(jqXHR, errorType, exception) {
				//console.log(\"error :\"+jqXHR.status +\":\"+jqXHR.responseText +\":\"+errorType+\":\"+exception);
			},
  complete 	: function(jqXHR, textStatus){
  			if (textStatus != \"success\"){
					//console.log(\"?complete :\"+jqXHR+\":\"+textStatus);
  			}
			}
});"

?>