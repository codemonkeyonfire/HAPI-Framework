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
namespace Modules\User;

use Modules\AbstractModule;

class LoginModule extends AbstractModule 
{
	public function run($PARAMETERS="")
	{
	    $this->template = "simple";

	    if(!isset($objURL))	$objURL = new \Handlers\Util\UrlHandler($this->objRegistry);
		$REQUEST_DATA = $this->objRegistry->Get_Value("REQUEST:DATA");
		
		$this->arrFormData["message"]  = "Please sign in";
		
		switch($REQUEST_DATA["act"])
		{
			case "login":
					
				if(!isset($objUser)) $objUser = new \Handlers\Users\UserHandler($this->objDatabase, $this->objRegistry);
				if(!isset($objUserSession)) $objUserSession = new \Handlers\Users\UserSessionHandler($this->objDatabase);
	
				$rst = $objUser->Authenticate($REQUEST_DATA["username"], $REQUEST_DATA["password"]);

				if($rst)
				{
				    
					$strTOKEN = $objUser->Generate_Token();

					$tan = $objUserSession->Create_Session($objUser->Get_ID(), $strTOKEN);

					$_SESSION["UAID"] = $strTOKEN;
					$_SESSION["tStr"] = $tan;
					$_SESSION["tCoord"] = $objUserSession->Get_Tan_Coord();
					 
					if(!empty($_SESSION["entryURL"]))
					{
					    ?><script>document.location.href='<?php echo $_SESSION["entryURL"]?>';</script><?php
					}else{
					    ?><script>document.location.href='<?php echo $objURL->Make("General", "dashboard")?>'</script><?php
					}
					
		
					exit();
				}else {
					$this->arrFormData["message"] = "Log-in failed";
				}
				break;
		}		
	
		
		$this->arrFormData["sitename"] = $this->objRegistry->Get_Value("SITE:NAME");
		$this->arrFormData["logo"] = $this->objRegistry->Get_Value("LOGIN:LOGO");
		$this->arrFormData["url"] = $objURL->Make("User", "Login", "", "");

	}
	
	public function display($PARAMETERS="")
	{
		$DATA = &$this->arrFormData;

		require('Views/v_Login.php');

		return true;
	}
	
}


	
