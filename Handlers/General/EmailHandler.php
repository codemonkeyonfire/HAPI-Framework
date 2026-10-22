<?php
/**
 * Database Handler
 * can only implement drivers
 * can only use other handlers if they are passed by reference
 * Do not reference Globals except for lib paths (explicitly pass values to functions)
 * can implement base class if and only if base class used by more than one handlers
*
* @author 
* @version 1.0
* @package insideArtCenter
* @subpackage common
* @date 6/14/12
*/
namespace Handlers\General;

class EmailHandler 
{
	private $Mail_Driver;
	private $from = false;
	
    function __construct(&$REGISTRY) 
    {
    	$mode = "sendmail";
    	    	 
    	$mailHost = $REGISTRY->Get_Value("SITE-MAILHOST");
    	
    	switch($mode)
    	{
    	    case "phpmailer":
    	        require_once 'drivers/driver_phpmailer.php';
    	        $this->Mail_Driver = new \Drivers\PHPMailerDriver($mailHost);
    	        break;
    	    case "sendmail":
    	        require_once 'drivers/driver_sendmail.php';
    	        $this->Mail_Driver = new \Drivers\EmailDriver($mailHost);
    	        break;
    	}
    }
    
    public function Set_From($ADDRESS, $NAME="")
    {
    	if(is_array($ADDRESS))
    	{
    		foreach ($ADDRESS as $addr)
    			$this->Mail_Driver->Set_From($addr);
    	}else{
    		$this->Mail_Driver->Set_From($ADDRESS, $NAME);
    	}
    	
    	$this->from = true;
    }
    
    public function Set_To($ADDRESS, $NAME="")
    {
    	if(is_array($ADDRESS))
    	{
    		foreach ($ADDRESS as $addr)
    			$this->Mail_Driver->Set_To($addr);
    	}else{
    		$this->Mail_Driver->Set_To($ADDRESS, $NAME);
    	}    	
    }
    
    public function Set_Subject($TEXT)
    {
    	$this->Mail_Driver->Set_Subject($TEXT);
    }
    
    public function Set_Message($TEXT)
    {
    	$this->Mail_Driver->Set_Message($TEXT);
    }
    
    public function Set_Bcc($ADDRESS, $NAME="")
    {
    	if(is_array($ADDRESS))
    	{
    		foreach ($ADDRESS as $addr)
    			$this->Mail_Driver->Set_Bcc($addr);
    	}else{
    		$this->Mail_Driver->Set_Bcc($ADDRESS, $NAME);
    	}    	
    }
    
    public function Send($TO="", $SUBJECT="", $MESSAGE="")
    {    	
    	if(!$this->from)
    		$this->Set_From("inside@artcenter.edu");
    		
    	return $this->Mail_Driver->Send($TO, $SUBJECT, $MESSAGE);
    }
}