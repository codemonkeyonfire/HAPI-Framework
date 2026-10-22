<?php
namespace Handlers\Util;

class EmailHandler
{
	private $objEMAIL;
	private $objRegistry;
	private $receipients;
	private $sender;
	private $subject;
	
	public function __construct($SETTINGS)
	{
		$this->objRegistry = $SETTINGS;
		$this->objEMAIL = new \Drivers\PHPMailer69Driver(true, $this->objRegistry->Get_Value("SMTP:HOST"), $this->objRegistry->Get_Value("SMTP:USERNAME"), $this->objRegistry->Get_Value("SMTP:PASSWORD"), $this->objRegistry->Get_Value("SMTP:PORT"));
	}	
	
	public function add_receipient($EMAIL)
	{
	    $this->receipients[] = $EMAIL;
	}
	
	public function set_from($EMAIL)
	{
	    $this->sender = $EMAIL;
	}

	public function set_subject($TEXT)
	{
	   $this->subject = $TEXT;	    
	}
	
	public function add_attachment($FILE_PATH)
	{
	    $this->objEMAIL->add_attachment($FILE_PATH);
	}
	
	public function set_body($CONTENT, $TOKENS="")
	{
	    
	    $html = $CONTENT;
	    
	    foreach($TOKENS as $find => $replace)
	    {
	        $html = str_replace("[[".$find."]]", $replace, $html);
	    }
	    
	    $text = strip_tags($html);
	    
	    $this->objEMAIL->add_text($text);
	    $this->objEMAIL->add_html($html);
	}
	
	public function Send($RCPT_ADDR="", $SUBJECT="", $SNDR_ADDR="")
	{
	    if($RCPT_ADDR!="")
	    {
	        $this->objEMAIL->Set_Recipient($RCPT_ADDR);
	    }else{
	        if($this->objEMAIL->DriverName == "PHPMailer")
	        {
	            foreach($this->receipients as $addr)
	            {
	                $this->objEMAIL->Set_Recipient($addr);
	            }
	        }else{
	           $this->objEMAIL->Set_Recipient(implode(",", $this->receipients));
	        }
	    }
	    
	    if($SUBJECT!="")
	    {
	        $this->objEMAIL->Set_Subject($SUBJECT);
	    }else{
	        $this->objEMAIL->Set_Subject($this->subject);
	    }
	    
	    if($SNDR_ADDR!="")
	    {
	        $this->objEMAIL->Set_Sender($SNDR_ADDR);
	    }else{
	        $this->objEMAIL->Set_Sender($this->sender);
	    }
	    	    
	    $this->objEMAIL->Send();
	}
	
	public function Send_Generic($TO, $FROM_ADDRESS, $FROM_NAME, $SUBJECT, $MESSAGE)
	{
	    $this->objEMAIL->Set_Recipient($TO);
	    $this->objEMAIL->Set_Sender($FROM_ADDRESS);
	    $this->objEMAIL->Set_Subject($SUBJECT);
	    
 
	    $text = str_replace("[[SITE]]", $this->objRegistry->Get_Value("SITE:NAME"), strip_tags($MESSAGE));
	    $html = str_replace("[[SITE]]", $this->objRegistry->Get_Value("SITE:NAME"), $MESSAGE);
	    
	    $text = str_replace("[[NAME]]", $FROM_NAME, $text);
	    $html = str_replace("[[NAME]]", $FROM_NAME, $html);
	    
	    $logo = "<img src=\"".$this->objRegistry->Get_Value("SITE:URL")."/images/".$this->objRegistry->Get_Value("SITE:LOGO")."\" width=\"400px;\">";
	    $text = str_replace("[[LOGO]]", $logo, $text);
	    $html = str_replace("[[LOGO]]", $logo, $html);
	    
	    $html = str_replace("[[LINK]]", $link, $html);
	    
	    $this->objEMAIL->add_text($text);
	    $this->objEMAIL->add_html($html);
	    
	    //$this->objEMAIL->add_attachment($PATH);
	    
	    $this->objEMAIL->Send();
	}
	
	public function Send_Invoice($TO, $FROM_ADDRESS, $FROM_NAME, $PDF_STR)
	{
	    $this->objEMAIL->Set_Recipient($TO);
	    $this->objEMAIL->Set_Sender($FROM_ADDRESS);
	    $this->objEMAIL->Set_Subject("Invoice");
	    
	    $text = "";
	    $html = "";
	    $state = "";
	    $root_path = $this->objRegistry->Get_Value("SITE:ROOT_PATH");
	    $f=fopen($root_path."/Views/emails/invoice.txt",'r');
	    while($line=fgets($f))
	    {
	        if(strstr($line, "##text##"))
	        {
	            $state = "text";
	            $line = "";
	        }
	        
	        if(strstr($line, "##html##"))
	        {
	            $state = "html";
	            $line = "";
	        }
	        
	        if($state == "text") $text .= $line;	        
	        if($state == "html") $html .= $line;
	    }
	    
	    $text = str_replace("[[SITE]]", $this->objRegistry->Get_Value("SITE:NAME"), $text);
	    $html = str_replace("[[SITE]]", $this->objRegistry->Get_Value("SITE:NAME"), $html);
	    
	    $text = str_replace("[[NAME]]", $FROM_NAME, $text);
	    $html = str_replace("[[NAME]]", $FROM_NAME, $html);
	    
	    $logo = "<img src=\"".$this->objRegistry->Get_Value("SITE:URL")."/images/".$this->objRegistry->Get_Value("SITE:LOGO")."\" width=\"400px;\">";
	    $text = str_replace("[[LOGO]]", $logo, $text);
	    $html = str_replace("[[LOGO]]", $logo, $html);
	    	    
	    //$html = str_replace("[[LINK]]", $link, $html);
	    
	    $this->objEMAIL->add_text($text);
	    $this->objEMAIL->add_html($html);
	    
	    $this->objEMAIL->add_attachment_string($PDF_STR, "invoice.pdf");
	    
	    $this->objEMAIL->Send();
	}
		
	public function Send_Email_Validation($TO, $CODE)
	{
		$this->objEMAIL->Set_Recipient($TO);
		$this->objEMAIL->Set_Sender("support@ferrera-tech.com");
		$this->objEMAIL->Set_Subject("HappyCat");
		
		$text = "";
		$html = "";
		$state = "";		
		
		$f=fopen($_SERVER['DOCUMENT_ROOT']."/Views/emails/emailValidate.txt",'r');
		while($line=fgets($f))
		{
			if(strstr($line, "##text##"))
			{
				$state = "text";
				$line = "";
			}
			
			if(strstr($line, "##html##"))	
			{
				$state = "html";
				$line = "";
			}
			
			if($state == "text")		
				$text .= $line;
							
			if($state == "html")
				$html .= $line;
						
		}
		
		$link = "https://".$_SERVER["SERVER_NAME"]."/validate.php?evc=".$DATA["code"];
		
		$text = str_replace("[[LINK]]", $link, $text);
		$html = str_replace("[[LINK]]", $link, $html);
		$html = str_replace("[[CODE]]", $DATA["code"], $html);
		 
		$this->objEMAIL->add_text($text);
		$this->objEMAIL->add_html($html);
		$this->objEMAIL->Send();
	}
	
	public function Send_Change_Password($ADDRESS, $PASSWORD_TOKEN, $URL)
	{
		$this->objEMAIL->Set_Recipient($ADDRESS);
		$this->objEMAIL->Set_Sender("support@ferrera-tech.com");
		$this->objEMAIL->Set_Subject("Account change request");
		
		$text = "";
		$html = "";
		$state = "";		
		
		if (!($f=fopen($_SERVER['DOCUMENT_ROOT']."/Views/emails/passwordChange.txt",'r'))) 
		{
			var_dump($php_errormsg);
		}
		

		while($line=fgets($f))
		{
			if(strstr($line, "##text##"))
			{
				$state = "text";
				$line = "";
			}
			
			if(strstr($line, "##html##"))	
			{
				$state = "html";
				$line = "";
			}
			
			if($state == "text")		
				$text .= $line;
							
			if($state == "html")
				$html .= $line;
						
		}
		
		$text = str_replace(array("[[TOKEN]]", "[[URL]]"), array($PASSWORD_TOKEN, $URL), $text);
		$html = str_replace(array("[[TOKEN]]", "[[URL]]"), array($PASSWORD_TOKEN, $URL), $html);
		 
		$this->objEMAIL->add_text($text);
		$this->objEMAIL->add_html($html);
		$this->objEMAIL->Send();
	}	
}