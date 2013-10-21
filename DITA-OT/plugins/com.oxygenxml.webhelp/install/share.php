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
$baseDir0 = dirname(dirname(__FILE__));
include $baseDir0.'/oxygen-webhelp/resources/php/init.php';

$version="@PRODUCT_VERSION@";

if (isset($_POST['host'])&&isset($_POST['user'])&&isset($_POST['passwd'])&&isset($_POST['db'])){
	$dbConnectionInfo = array(
			'dbHost' => $_POST['host'],
			'dbName' => $_POST['db'],
			'dbPassword' => $_POST['passwd'],
			'dbUser' => $_POST['user']
	);
	try{
		$db= new RecordSet($dbConnectionInfo,false,true);
		$prds=$db->Open("Select product,value from webhelp where parameter='name' and version='".$version."'; ");
		if ($prds>0){
			echo "<div class=\"title\">Display comments from</div>
			<div class=\"desc\">Share other products comments (having the same version) with this one. You must select one or more products from the list. Hold down the Ctrl (windows) / Command (Mac) button to select multiple options. </div>
			<table>
			<tr>
			<td>Existing products sharing the same database
			</td>
			<td>";			
							
			echo "<select multiple=\"multiple\" name=\"shareWith[]\" size=\"5\">";			
			while ($db->MoveNext()){
				$product=$db->Field('product');
				$name=$db->Field('value');
				echo "<option value=\"".$product."\">".$name."</option>";
			}
			echo "</select>";
			echo "</td>
			</tr></table></div>";
		}
	}catch (Exception $ex){
		echo "<br/>Could not connect to database using specified informations:";
		echo "<table class=\"info\">";
		echo "<tr><td>Host </td><td>".$dbConnectionInfo['dbHost']."</td></tr>";
		echo "<tr><td>Database </td><td>".$dbConnectionInfo['dbName']."</td></tr>";
		echo "<tr><td>User </td><td>".$dbConnectionInfo['dbUser']."</td></tr>";
		echo "</table>";
		$continue=false;
	}
}

?>