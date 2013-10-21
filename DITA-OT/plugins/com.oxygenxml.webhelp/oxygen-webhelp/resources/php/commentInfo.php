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
if (isset($_POST["qInfo"]) && trim($_POST["qInfo"]) !="" && ($_POST["qInfo"]=="true")){	
	$pName=(isset($_POST['productName']) ? $_POST['productName'] :"");
	$pVersion=(isset($_POST['productVersion']) ? $_POST['productVersion'] : "");
	$fullUser=base64_encode($pName."_".$pVersion."_user");
	$comment = new Comment($dbConnectionInfo,"",$fullUser);
	$vList=$comment->queryInfo();
	$toPrint="";	
	if (count($vList)>0){
	$idx=0;
	$toPrint.="<div class='listTitle'>".Utils::translate("productsListTitle")."</div>";
	$toPrint.="<div class='listTitleV'>".Utils::translate("versionsListTitle")."</div>";
	$toPrint.="<div class='products'>";
	foreach ($vList as $origProduct => $versions){	
		$product=$origProduct;		
		$toPrint.="<div class='p_selectable' id='p_$idx' onclick=\"showVersions('$idx','$product');\">".$product."</div>";					
		$idx++;
	}
	$toPrint.="</div>";
	$toPrint.="<div class='versions' style='display:none;'>";
	$idx=0;
	foreach ($vList as $origProduct => $versions){
		$toPrint.="<div class='product_Versions' id='v_$idx' style='display:none;'>";
		$vidx=0;
		foreach ($versions as $version){
			$toPrint.="<div id='ver_".$idx."_".$vidx."' class='selectable' onclick=\"setExpVersion(this,'$version');\">".$version."</div>";			
			$vidx++;
		}
		$toPrint.="</div>";	
		$idx++;
	}
	$toPrint.="</div>";
	}else{
		$toPrint.="<div class='listTitle'>".Utils::translate("info.noComments")."</div>";
	}
	echo $toPrint;
} else{
	echo "No data to query!";
}
?>