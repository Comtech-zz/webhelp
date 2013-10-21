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
$ses=Session::getInstance();

if ((isset($_POST["productName"]) && trim($_POST["productName"]) !="")		
	&& (isset($_POST["productVersion"]) && trim($_POST["productVersion"]) !="")){
	
	$product=(isset($_POST['productName']) ? $_POST['productName'] :"");
	$version=(isset($_POST['productVersion']) ? $_POST['productVersion'] : "");
	
	if (Utils::isAdministrator($product, $version)){
		if (isset($_POST["inPage"]) && trim($_POST["inPage"]) =="true"){
			$clean=Utils::getParam($_POST, 'clean');
			$filter=new NoFilter();			
			$cellRenderer = new DefaultCellRenderer("span");
			if ($clean=='true'){
				$filter=new UnconfirmedUserFilter(-7, "date");
			}else{
				$filter=new ConfirmedUserFilter(-7, "date");
			}
			$exporter = new InLineExporter('userId',array('userId'),array(17,25,30,20));
			$exporter->setFilter($filter);
			$exporter->setCellRenderer($cellRenderer);
			$tableExporter = new TableExporter("users", $dbConnectionInfo);
			$tableExporter->export($exporter,"userId,userName,name,email,date","ORDER BY date DESC");
			echo $exporter->getContent();
		}else{
// 		$exporter = new XmlExporter("comments");
// 		$comment->exportForPage($info,$exporter);
// 		header('Content-Description: File Transfer');
// 		header('Content-Type: text/xml');
// 		header('Content-Disposition: attachment; filename=comments_'.$fName.'_'.$fVersion.'.xml');
// 		header('Content-Transfer-Encoding: binary');
// 		header('Expires: 0');
// 		header('Cache-Control: must-revalidate');
// 		header('Pragma: public');
// 		ob_clean();
// 		flush();
// 		echo $exporter->getContent();
//   	exit;		
		}	
	}	
			
}else{
	echo "No data to export as comment!";
}
?>