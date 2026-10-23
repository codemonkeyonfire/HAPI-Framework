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
 
//**************************************************************************************** initialize
namespace Modules\Layout;

use Modules\AbstractModule;

class HeaderModule extends AbstractModule
{

	public function run($PARAMETERS="")
	{
		
	    if(!isset($objURL))	$objURL = new \Handlers\Util\UrlHandler($this->objRegistry);
			
		$submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
		$USER_ID = $this->objRegistry->Get_Value("USER:ID");
		$USERNAME = $this->objRegistry->Get_Value("USER:USERNAME");

		$this->arrViewData["logo"] =  "/images/".$this->objRegistry->Get_Value("SITE:LOGO");
		
		$email = $this->objRegistry->Get_Value("SITE:EMAIL");
		$phone = $this->objRegistry->Get_Value("SITE:PHONE");
		[$areacode, $sector, $zone] = explode("-", $phone);
		$this->arrViewData["features"][] = ["icon" => "phone-alt", "text" => "1({$areacode}){$sector}-{$zone}", "url" => "tel:+1({$areacode}){$sector}-{$zone}"];
		$this->arrViewData["features"][] = ["icon" => "envelope", "text" => $email, "url" => "mailto:{$email}"];

		
	}
	
	public function display($PARAMETERS="")
	{
		if($this->objRegistry->Get_Value("IS_LIVE:EDIT_CASHFLOW") == "NO")
		{
			echo "This Page is currently unavailable";
			exit();
		}
	
		$DATA = &$this->arrViewData;
		include "Views/v_Header.php";

		return true;
	}	
	
}

