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
class OxyBagHandler{

	/**
	 * Exceptions bag
	 * @var String
	 */
	private $bag;
	/**
	 * email List of administrators witch will be receiving the internal errors
	 * @var String
	 */
	private $to;
	private $count;
	private $errorCount;
	private $sendIt;
    private $bagJs;

	/**
	 * Send errors when errorCount is reached
	 *
	 * @param int $errorCount default is 3
	 */
	function __construct($errorCount=0){
        $this->bagJs="";
		$this->bag="";
		$this->errorCount=$errorCount;
		$this->count=0;
		$this->sendIt = defined("__SEND_ERRORS__") && __SEND_ERRORS__;
		$this->to=array();
		if (defined('__ADMIN_EMAIL__') && strlen(trim(__ADMIN_EMAIL__))>0){
			$this->to[]=__ADMIN_EMAIL__;
		}
	}
	private function getRealIpAddr(){
		$ip="unknown";
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	function processException($exceptionMessage){
      $this->bagJs.="[".$this->getRealIpAddr()."] - ".$exceptionMessage."<br/>";
		if ($this->sendIt){
			$this->bag.="[".$this->getRealIpAddr()."] - ".$exceptionMessage."<br/>";
			$this->count++;
			$this->send();
		}else{
			error_log("[".$this->getRealIpAddr()."] - ".$exceptionMessage);
		}
	}
	private function getEmails(){
		global $dbConnectionInfo;
		$this->to=array();
		if (defined('__ADMIN_EMAIL__') && strlen(trim(__ADMIN_EMAIL__))>0){
			$this->to[]=__ADMIN_EMAIL__;
		}
		if (defined("__SEND_ERRORS__") && __SEND_ERRORS__){

			try{
				$ds= new RecordSet($dbConnectionInfo,false,true);
				$ds->open("SELECT email FROM users WHERE level='admin';");
				while ($ds->MoveNext()) {
					$this->to[]=$ds->Field('email');
				}
				$ds->close();
			}catch(Exception $e){
				// ignore
				$msg=date("Y-m-d H:i:s").": Error:[".$e->getCode()."] message:[".$e->getMessage()."]	file:[".$e->getFile()."] line:[".$e->getLine()."]";
				$this->bag.="[".$this->getRealIpAddr()."] - ".$msg."<br/>";
				$this->count++;
				$this->sendInternal(false);
			}
		}
	}
	private function sendInternal($fetchEmails=true){
		if ($this->sendIt){
			if ($fetchEmails){
				$this->getEmails();
			}
			foreach ($this->to as $emailTo){
				$mail = new Mail();
				$mail->Subject("[".$_SERVER["SERVER_NAME"]."] ERROR ");
				$mail->To($emailTo);
				$mail->From(__EMAIL__);
				$mail->Body($this->bag);
				@$mail->Send();
			}
		}
		error_log($this->bag);

	}
    public function poolFromJs(){
      $toReturn=$this->bagJs;
      $this->bagJs="";      
      return $toReturn; 
    }
	public function send(){
		if ((count($this->to)>0) && ($this->count>=$this->errorCount)){
			$this->sendInternal();
			$this->count=0;
			$this->bag="";
		}
		error_log($this->bag.$this->count."/".$this->errorCount);
	}
}