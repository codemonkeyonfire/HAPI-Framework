<?php
/**
 * Email Driver
 *
 * formats config varibles and reads values from that format
 * @author Carlos Ferrera
 * @version 1.0
 * @package configuration
 */
/**
 * The first example class, this is in the same package as the
 * procedural stuff in the start of the file
 * @package configuration
 * @subpackage classes
 */
namespace Drivers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerDriver
{
		
	private $RECIPIENT;
	private $SENDER;
	private $SENDER_NAME;
	private $SUBJECT;
	private $BODY_PARTS;
	private $BOUNDRY;
	private $HEADERS;
	private $hasATTACHMENTS;
	
	private $Mailer;
	public $DriverName;
	
	
	public function __construct($IS_SMTP=false, $HOST="", $USERNAME="", $PASSWORD="", $PORT="587")
	{
     	if($this->BOUNDRY == "")
     		$this->BOUNDRY = '=' . md5( uniqid ( rand() ) ); 
     		
     	$this->hasATTACHMENTS = false;	
     	
     	$this->Mailer = new PHPMailer(true);
     	$this->DriverName = "PHPMailer";
     	
     	if($IS_SMTP)
     	{
     	    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
     	    $this->Mailer->isSMTP();                                            // Send using SMTP
     	    $this->Mailer->Host       = $HOST;                    // Set the SMTP server to send through
     	    $this->Mailer->SMTPAuth   = true;                                   // Enable SMTP authentication
     	    $this->Mailer->Username   = $USERNAME;                     // SMTP username
     	    $this->Mailer->Password   = $PASSWORD;                               // SMTP password
     	    $this->Mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
     	    $this->Mailer->Port       = $PORT;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
     	    
     	    $this->Mailer->SMTPOptions = array(
     	        'ssl' => array(
     	            'verify_peer' => false,
     	            'verify_peer_name' => false,
     	            'allow_self_signed' => true
     	        )
     	    );
     	    
     	    //$this->Mailer->SMTPDebug = SMTP::DEBUG_SERVER; 
     	    //$this->Mailer->SMTPDebug  = SMTP::DEBUG_CONNECTION;
     	}
     	    
     	
	}	

	public function __destruct()
	{
		
	}
	

	public function Send()
	{
	    try  {
	       $this->Mailer->send();
    	} catch (Exception $e) {
    	    echo "Message could not be sent. Mailer Error: {$this->Mailer->ErrorInfo}";
    	}
	}	

	public function Set_Recipient($ADDR, $NAME="")
	{
	    $this->Mailer->addAddress($ADDR, $NAME);
	}
	
	public function Set_ReplyTo($ADDR, $NAME="")
	{
	    $this->Mailer->addReplyTo($ADDR, $NAME);
	}
	
	public function Set_Sender($ADDR, $NAME="")
	{
	    $this->Mailer->setFrom($ADDR, $NAME);
	}
	
	public function Set_CC($ADDR, $NAME="")
	{
	    $this->Mailer->addCC($ADDR, $NAME);
	}
	
	public function Set_BCC($ADDR, $NAME="")
	{
	    $this->Mailer->addBCC($ADDR, $NAME);
	}
	
	public function Set_Subject($VAL)
	{
	    $this->Mailer->Subject = $VAL;
	}	
	
     function add_attachment($PATH)
     {
    	
         $this->Mailer->addAttachment($PATH);                           
         $this->hasATTACHMENTS = true;	                 
     } 	
     
     function add_attachment_string($DATA, $FILENAME)
     {
         $this->Mailer->addStringAttachment($DATA, $FILENAME) ;	
     }
     
     function add_text($TEXT)
     {
         $this->Mailer->isHTML(false);
         $this->Mailer->Body = $TEXT;
     }
     
     function add_html($HTML, $TEXT="")
     {
         $this->Mailer->isHTML(true);
         $this->Mailer->Body = $HTML;
         $this->Mailer->AltBody = $TEXT;
		return false;	
     }
    
	function getContentType($FileName)
	{
		return false;
	}      
	
}