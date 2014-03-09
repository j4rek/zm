<?php
class SMTPClient {
	function SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body)
	{ 
	//echo ".1";

	$this->SmtpServer = $SmtpServer;
	$this->SmtpUser = base64_encode ($SmtpUser);
	$this->SmtpPass = base64_encode ($SmtpPass);
	$this->from = $from;
	$this->to = $to;
	$this->subject = $subject;
	$this->body = $body;

		if ($SmtpPort == "")  {
			$this->PortSMTP = 25;
		}else{
			$this->PortSMTP = $SmtpPort;
		}


	}

	function SendMail () {
	//echo ".2". $this->SmtpServer . $this->PortSMTP;
		if ($SMTPIN = fsockopen ($this->SmtpServer, $this->PortSMTP)) {
		//echo ".3";
	           fputs ($SMTPIN, "EHLO ".$HTTP_HOST."\r\n");  
	           $talk["hello"] = fgets ( $SMTPIN, 1024 ); 
	                   
			   fputs($SMTPIN, "auth login\r\n");
			   $talk["res"]=fgets($SMTPIN,1024);
				fputs($SMTPIN, $this->SmtpUser."\r\n");
			    $talk["user"]=fgets($SMTPIN,1024);
			    
			    fputs($SMTPIN, $this->SmtpPass."\r\n");
				$talk["pass"]=fgets($SMTPIN,256);
				   	    
	           fputs ($SMTPIN, "MAIL FROM: <".$this->from.">\r\n");  
	           $talk["From"] = fgets ( $SMTPIN, 1024 );  
	           fputs ($SMTPIN, "RCPT TO: <".$this->to.">\r\n");  
	           $talk["To"] = fgets ($SMTPIN, 1024); 

	           fputs($SMTPIN, "DATA\r\n");
				$talk["data"]=fgets( $SMTPIN,1024 );

				// armo header
				$headers = "";
				$headers .="To: <".$this->to.">\r\n";
				$headers = "From: ZoomInmobiliario <".$this->from.">\r\n";
				$headers .= "X-Priority: 1\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "X-MSMail-Priority: High\r\n";
				$headers .= "Importance: 1\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				//echo $headers;
				fputs($SMTPIN, "To: <".$this->to.">\r\nFrom: <".$this->from.">\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nSubject:".$this->subject."\r\n\r\n\r\n".$this->body."\r\n.\r\n");
				
				//fputs($SMTPIN, "$headers\r\nSubject:".$this->subject."\r\n\r\n\r\n".$this->body."\r\n.\r\n");
				$talk["send"]=fgets($SMTPIN,256);
	           
	           //CLOSE CONNECTION AND EXIT ... 
			   
	           fputs ($SMTPIN, "QUIT\r\n");  
	           fclose($SMTPIN); 
			 //  
		}
	return $talk;
	}

} 
?>