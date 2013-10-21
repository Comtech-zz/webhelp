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
class Mail{

	private $subject;
	private $from;
	private $replyTo;
	private $sendTo = array();
	private $ccTo = array();
	private $bccTo = array();
	private $body;
	private $headers;

	function __construct(){
		
	}


	function Subject( $subject ){
		$this->subject=strtr( $subject, "\r\n" , "  " );
	}

	function From( $from ){
		if( ! is_string($from) ) {
			throw new Exception("Class Mail: error, From is not a string");
		}
		$this->from=$from;
	}

	function ReplyTo( $address ){

		if( ! is_string($address) ){
			throw new Exception("Class Mail: error, Reply-To is not a string");
		}
		$this->replyTo-$address;
	}


	function To( $to ){
		if( is_array($to) ){
			$this->sendTo= $to;
		}else{
			$this->sendTo[] = $to;
		}

	}

	function Cc( $cc ){
		if(is_array($cc)){
			$this->ccTo= $cc;
		}else{
			$this->ccTo[]= $cc;
		}


	}
	function Bcc( $bcc ){
		if( is_array($bcc) ) {
			$this->bccTo = $bcc;
		} else {
			$this->bccTo[]= $bcc;
		}

	}

	function Body( $body){
		$this->body = $body;
	}

	function Organization( $org ){
		if(trim($org != "")){
			$this->organization=$org;
		}
	}


	private function build(){
		$this->headers = "";
		
		if(count($this->ccTo) > 0 ){
			$this->headers.="CC: ".implode( ", ", $this->ccTo )."\r\n";
    }
    
    if (trim($this->replyTo)!=""){
      $this->headers.="Reply-To: ".$this->replyTo."\r\n";
    }
    $this->headers.="From: ".$this->from."\r\n";
    

		if( count($this->bccTo) > 0 ){
			$this->headers.="BCC: ".implode( ", ", $this->bccTo)."\r\n";
		}
		// $this->xheaders['BCC'] = implode( ", ", $this->abcc );
		$this->headers.="X-Mailer: oXygen Webhelp system\r\n";
		$this->headers.= "MIME-Version: 1.0\r\n";
		$this->headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	}

	function Send(){
		$this->build();

		$this->sendTo = implode( ", ", $this->sendTo);

		$result = @mail( $this->sendTo, $this->subject, $this->body, $this->headers);
		return $result;
	}




} // class Mail

?>