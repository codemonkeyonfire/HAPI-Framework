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

class FooterModule extends AbstractModule
{

	public function run($PARAMETERS="")
	{
		
		if(!isset($objURL))	$objURL = new \Handlers\Util\UrlHandler($this->objRegistry);
			
		$submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
		$USER_ID = $this->objRegistry->Get_Value("USER:ID");

				
		$this->arrViewData["facebookURL"] = "https://www.facebook.com/s";
		$this->arrViewData["twitterURL"] = "http://www.twitter.com/";
		$this->arrViewData["linkedinURL"] = "https://www.linkedin.com/in/";
			
	}
	
	public function display($PARAMETERS="")
	{
		if($this->objRegistry->Get_Value("IS_LIVE:EDIT_CASHFLOW") == "NO")
		{
			echo "This Page is currently unavailable";
			exit();
		}

		$DATA = &$this->arrViewData;
        include "Views/v_Footer.php";
		
		
		return true;
	}	
	
}

