<?php
 /*************************************************************************
 * 
 *  (c)2011 Carlos Ferrera
 *  All Rights Reserved.
 * 
 * NOTICE:  
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Carlos Ferrera.
 *
 * Email: irondogg@gmail.com
 */
namespace Modules\General;

use Modules\AbstractModule;
use Handlers\General\DatabaseHandler;
use Handlers\General\RegistryHandler;
 
define("APP", 0);
define("MOD", 1);
define("ITM", 2);
 
class InitModule extends AbstractModule 
{
	private $App;
	private $MainMod;
	private $itm;
	
	public function run($PARAMETERS="")
	{		
		ob_start();
	
		date_default_timezone_set('America/Los_Angeles');	
		
		$SERVER = $_SERVER['HTTP_HOST'];
		
		$basepath = "/";
		
		$sitename = "HAPI Framework";
		$protocol = "https://";
		
		$siteEmail = "";
		$sitePhone = "";
		$siteFacebook = "";
		$siteTwitter = "";
		$siteLinkedin = "";
	
		switch ($SERVER)
		{
		    case "my.website.com":
		        $DB["Server"] = "192.168.0.1";
		        $DB["User"] = "test";
		        $DB["Password"] = "changeme";
		        $DB["Database"] = "hapi";
		        
		        $sitename = "website.com";
		        $protocol = "https://";
				
				$siteEmail = "contact@website.com";
				$sitePhone = "123-123-1234";
				$siteFacebook = "https://www.facebook.com/website";
				$siteTwitter = "http://www.twitter.com/website";
				$siteLinkedin = "https://www.linkedin.com/in/website";
		        break;
		}			
		
				
		if(empty($this->objDatabase)) $this->objDatabase = new DatabaseHandler($DB);	
		if(empty($this->objRegistry)) $this->objRegistry = new RegistryHandler($this->objDatabase);

	    $url_parts = parse_url($_SERVER['REQUEST_URI']);
	    $urlPath = substr($url_parts["path"], strlen($basepath));
	    $route_parts = explode("/", $urlPath);	    	    

	    if(empty($route_parts[APP]))
		{
		    $this->App = "Page";
		    $this->MainMod = "Display";
		    $this->itm = "Home";
		}else{
		    $this->App = $route_parts[APP];
		    
		    if(empty($route_parts[MOD]))
		    {
		        $this->MainMod = "Home";
		    }else{
		        $this->MainMod = $route_parts[MOD];
		        
		        if(empty($route_parts[ITM]))
		        {
		            if($this->MainMod=="Display")
		            {
		                $this->itm = "Home";
		            }
		        }else{
		            $this->itm = $route_parts[ITM];
		        }
		    }
		}
              
        $this->objRegistry->Load_From_DB();
        
		$this->objRegistry->Set_Value("SITE:APP_NAME", ucfirst($this->App));
		$this->objRegistry->Set_Value("SITE:MAIN_MODULE", ucfirst($this->MainMod));
		$this->objRegistry->Set_Value("SITE:MODULE_ITEM", $this->itm);
		
		$PARAMETERS["itm"] = $this->itm;
			
		$this->objRegistry->Set_Value("DATABASE:READ_SERVERS", $DB["Server"]);
		$this->objRegistry->Set_Value("DATABASE:USER", $DB["User"]);
		$this->objRegistry->Set_Value("DATABASE:PASSWORD", $DB["Password"]);
		$this->objRegistry->Set_Value("DATABASE:DATABASE", $DB["Database"]);
		
		$this->objRegistry->Set_Value("SITE:NAME", $sitename);
		$this->objRegistry->Set_Value("SITE:BASE_PATH", $basepath);
				
		$url = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$basepath;
		$url = rtrim($url, "/");
		
		$this->objRegistry->Set_Value("SITE:URL", $url);
		
		$this->objRegistry->Set_Value("SITE:LOGO", "logo.png");
		
		$document_root = $_SERVER['DOCUMENT_ROOT'].$basepath;
	
		$this->objRegistry->Set_Value("SITE:ROOT_PATH", $document_root);
		$this->objRegistry->Set_Value("SITE:PROTOCOL", $protocol);
		$this->objRegistry->Set_Value("SITE:HOSTNAME", $_SERVER['HTTP_HOST']);
		$this->objRegistry->Set_Value("SITE:SCRIPTNAME", $_SERVER['SCRIPT_NAME']);
		$this->objRegistry->Set_Value("SITE:PORT", "");
		
		$this->objRegistry->Set_Value("SITE:EMAIL", $siteEmail);
		$this->objRegistry->Set_Value("SITE:PHONE", $sitePhone);
		$this->objRegistry->Set_Value("SITE:FACEBOOK", $siteFacebook);
		$this->objRegistry->Set_Value("SITE:TWITTER", $siteTwitter);
		$this->objRegistry->Set_Value("SITE:LINKEDIN", $siteLinkedin);
		
		$this->objRegistry->Set_Value("MODULES:NO_AUTH", ["api/*", "User/LogIn"]);
				
		$this->objRegistry->Set_Value("SMTP:HOST", "smtp.email.com");
				
		$this->objRegistry->Set_Value("SMTP:USERNAME", "services@email.com");
		$this->objRegistry->Set_Value("SMTP:PASSWORD", "changeme");
		$this->objRegistry->Set_Value("SMTP:PORT", "465");
		//$this->objRegistry->Set_Value("SMTP:PORT", "587");
        
		$this->objRegistry->Set_Value("ENCRYPT:HASH_PEPPER", "kj54634kj6h45k6h34k");
				
		$this->objRegistry->Set_Value("UPLOADFOLDER", $document_root."repos");
		
		$this->objRegistry->Set_Value("LOGIN:BACKGROUND", $url."/images/bg/clouds.jpg");
		$this->objRegistry->Set_Value("LOGIN:LOGO", $url."/images/logo_white.png");
		
		
		$DEBUG_MODE = (isset($PARAMETERS["dbug"]))?"on":"off";	
		$this->objRegistry->Set_Value("SITE:DEBUG_MODE", $DEBUG_MODE);
			
		$this->objRegistry->Add_CSS("/3rdParty/bootstrap/css/bootstrap.min.css");
		$this->objRegistry->Add_CSS("/3rdParty/fontawesome-css/css/all.min.css");
		$this->objRegistry->Add_CSS("/css/style.css");
		
		$this->objRegistry->Add_JS("/3rdParty/jquery.js", "header");
		$this->objRegistry->Add_JS("/3rdParty/bootstrap/js/bootstrap.bundle.min.js");
		
		$this->objRegistry->Set_Value("REQUEST:DATA", $PARAMETERS);
	}
	
	public function display($PARAMETERS="")
	{
		return true;
	}
	
	public function get_registry()
	{
		return $this->objRegistry;
	}
	
	public function get_database()
	{
		return $this->objDatabase;
	}

	public function get_app_name()
	{
		return 	$this->App;
	}
	
	public function get_main_module()
	{
		return 	$this->MainMod;
	}
}
?>