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

class EmailDriver
{
		
	private $RECIPIENT;
	private $SENDER;
	private $SENDER_NAME;
	private $SUBJECT;
	private $BODY_PARTS;
	private $BOUNDRY;
	private $HEADERS;
	private $hasATTACHMENTS;
	
	public $DriverName;
	
	
	public function __construct()
	{
	    $this->DriverName = "sendmail";
	    
     	if($this->BOUNDRY == "")
     		$this->BOUNDRY = '=' . md5( uniqid ( rand() ) ); 
     		
     	$this->hasATTACHMENTS = false;	
     	$this->BODY_PARTS = [];
	}	

	public function __destruct()
	{
		
	}
	

	public function Send()
	{
		$headers = "From: " . $this->SENDER_NAME . " <" . $this->SENDER . ">\r\n";
		$headers.= "MIME-Version: 1.0\n";
		$parts_type = ($this->hasATTACHMENTS)?"mixed":"alternative";
		$headers.= "Content-Type: multipart/".$parts_type."; boundary=\"$this->BOUNDRY\"";	

		$message = "";	
		
		foreach($this->BODY_PARTS as $part)
		{
			$message .= "--".$this->BOUNDRY."\r\n";
			$message .= $part;	
		}
		
		$message .= "--".$this->BOUNDRY."--\r\n";
		
		return mail($this->RECIPIENT, $this->SUBJECT, $message, $headers);
	}	
	
	public function Set_Recipient($ADDR, $NAME="")
	{
		$this->RECIPIENT = $ADDR;
	}
	
	public function Set_Sender($ADDR, $NAME="")
	{
		$this->SENDER = $ADDR;
		$this->SENDER_NAME = $NAME;
	}
	
	public function Set_Subject($VAL)
	{
		$this->SUBJECT = $VAL;
	}	
	
     function add_attachment($file)
     {
    	
         $fname = substr(strrchr($file, "/"), 1);
         $data = file_get_contents($file);
         $i = count($this->BODY_PARTS);
         $content_id = "part$i." . sprintf("%09d", crc32($fname)) . strrchr($this->RECIPIENT, "@");
         $this->BODY_PARTS[$i] = "Content-Type: ".mime_content_type($file)."; name=\"$fname\"\r\n" .
                           "Content-Transfer-Encoding: base64\r\n" .
                           "Content-ID: <$content_id>\r\n" .
                           "Content-Disposition: attachment;\n" .
                           " filename=\"$fname\"\r\n" .
                           "\n" .
                           chunk_split( base64_encode($data), 68, "\n");
                           
          $this->hasATTACHMENTS = true;	                 
               
         return $content_id;
     } 	
     
     function add_attachment_string($DATA, $FILENAME, $TYPE)
     {
         $i = count($this->BODY_PARTS);
         $content_id = "part$i." . sprintf("%09d", crc32($FILENAME)) . strrchr($this->RECIPIENT, "@");
         $this->BODY_PARTS[$i] = "Content-Type: $TYPE; name=\"$FILENAME\"\r\n" .
                           "Content-Transfer-Encoding: base64\r\n" .
                           "Content-ID: <$content_id>\r\n" .
                           "Content-Disposition: attachment;\n" .
                           " filename=\"$FILENAME\"\r\n" .
                           "\n" .
                           chunk_split( base64_encode($DATA), 68, "\n");
                           
         $this->hasATTACHMENTS = true;	
         
         return $content_id;    	
     }
     
     function add_text($TEXT)
     {
		$message = "Content-Type: text/plain; charset=UTF-8\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= "$TEXT\n\n";   

		$this->BODY_PARTS[]	= $message;
     }
     
     function add_html($HTML)
     {
		$message = "Content-Type: text/html; charset=UTF-8\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= "$HTML\n\n";   

		$this->BODY_PARTS[]	= $message;	
     }
    
	function getContentType($FileName)
	{
		$inFileName = basename($inFileName);

        if(strrchr($FileName,".") == false)
        {
			return "application/octet-stream";
		}

        $extension = strrchr($FileName,".");
        switch($extension){
			case ".gif":	return "image/gif";
			case ".gz":		return "application/x-gzip";
            case ".htm":	return "text/html";
            case ".html":	return "text/html";
            case ".jpg":	return "image/jpeg";
            case ".tar":	return "application/x-tar";
			case ".txt":	return "text/plain";
            case ".zip":    return "application/zip";
            default:		return "application/octet-stream";
        }
		return "application/octet-stream";
	}      
	
}