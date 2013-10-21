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
 * Export data in xml format
 *
 * @author serban
 *
 */
class InLineExporter implements IExporter{
	/**
	 * exported content
	 *
	 * @var String
	 */
	private $toReturn;
	/**
	 * Total exported rows
	 *
	 * @var int
	 */
	private $rows;

	private $ignoredFields;

	private $columnSizes;
	/**
	 * Cell renderer
	 * 
	 * @var ICellRenderer
	 */
	private $cellRenderer;
	/**
	 * Row filter
	 * @var IFilter
	 */
	private $filter;
	private $hasLines;
	private $idField;
	/**
	 * Constructor
	 * 	
	 * @param array $ignoredFields - fields to be ignored in view
	 * @param array $columnSizes - custom column size for each selected field 
	 */
	function __construct($idField,$ignoredFields=null,$columnSizes=null){
		$this->toReturn="";
		$this->idField=$idField;
		$this->rows=0;
		$this->ignoredFields=$ignoredFields;
		$this->columnSizes=$columnSizes;
		$this->hasLines=false;
	}
	
	function setFilter($filter){
		$this->filter=$filter;
	}
		
	/**
	 * Set cell renderer
	 * 
	 * @param ICellRenderer $cellRenderer
	 */
	function setCellRenderer($cellRenderer){
		$this->cellRenderer=$cellRenderer;
	}
	/**
	 * Export one row
	 * @param Array $AssociativeRowArray - array containing fieldName=>fieldValue
	 */
	function exportRow($AssociativeRowArray){
		if (!$this->filter->filter($AssociativeRowArray)){
			$this->hasLines=true;
		$width=20;
		if ($this->rows==0){
			$this->toReturn.="<div class=\"tbHRow\">";
			$column=0;
			foreach ($AssociativeRowArray as $field => $value){
				if (!in_array($field, $this->ignoredFields)){
					if ($this->columnSizes!=null){
						if ($this->columnSizes[$column]){
							$width=$this->columnSizes[$column];
						}else{
							$width=11;
						}
					}
					$this->toReturn.="<div class=\"tbCell\" style=\"width:$width%;\">".Utils::translate("label.tc.".$field)."</div>";
					$column++;
				}
			}
			if ($this->columnSizes!=null){
				$width=$this->columnSizes[count($this->columnSizes)-1];
			}
			//$this->toReturn.="<div class=\"tbCell\" style=\"width:$width%;\"><div>".Utils::translate("selected")."</div></div>";
			$this->toReturn.="</div>";
		}
		$this->rows++;
		$this->toReturn.="<div class=\"tbRow\">";
		$column=0;
		$id=-1;
		foreach ($AssociativeRowArray as $field => $value){
			$this->rows++;
			if ($field==$this->idField){
				$id=$value;
				if ($this->cellRenderer!=null){
				$this->cellRenderer->setAName($id);
			}
			}
			if (!in_array($field, $this->ignoredFields)){
				if ($this->columnSizes!=null){
					if ($this->columnSizes[$column]){
						$width=$this->columnSizes[$column];
					}else{
						$width=11;
					}
				}
				$renderedValue=$value;
				if ($this->cellRenderer!=null){
					$renderedValue=$this->cellRenderer->render($field, $value);
				}
				$this->toReturn.="<div class=\"tbCell\" style=\"width:$width%;\">".$renderedValue."</div>";
				$column++;
			}
		}		
		$this->toReturn.="<div class=\"tbCell\"><input type=\"checkbox\" class=\"cb-element\" value=\"$id\" onclick=\"addToDelete($id);\"/></div>";
		$this->toReturn.="</div>";
		}else{			
			// row filtered
	}
	}

	function getContent(){
		if ($this->hasLines){
			$this->toReturn="<div class=\"table\">".$this->toReturn;
		$this->toReturn.="</div>";
		$this->toReturn.="</div>";
		}
		return  $this->toReturn;
	}
	function getFilter(){
		return $this->filter;
}
}
?>