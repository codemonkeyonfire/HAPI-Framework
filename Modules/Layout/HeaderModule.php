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
	
		//$this->arrViewData["title"] = $this->objRegistry->Get_Value("SITE:NAME");		
		$this->arrViewData["logo"] =  "/images/".$this->objRegistry->Get_Value("SITE:LOGO");
		
				
		$this->arrViewData["features"][] = ["icon" => "phone-alt", "text" => "1(123)123-1234", "url" => "tel:+1(123)123-1234"];
		$this->arrViewData["features"][] = ["icon" => "envelope", "text" => "test@test.com", "url" => "mailto:test@test.com"];

		
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

