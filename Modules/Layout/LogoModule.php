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

class LogoModule extends AbstractModule
{

	public function run($PARAMETERS="")
	{
		
		if(!isset($objURL)) $objURL = new \Handlers\Util\UrlHandler($this->objRegistry);
			
			
		$submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
		$USER_ID = $this->objRegistry->Get_Value("USER:ID");
		$USERNAME = $this->objRegistry->Get_Value("USER:USERNAME");
		
		$basepath = $this->objRegistry->Get_Value("SITE:BASE_PATH");
		$currentApp = $this->objRegistry->Get_Value("SITE:CURRENT_APP");
				
		$this->arrViewData["url"] ="#";
		
		$image = $this->objRegistry->Get_Value("SETTING:SITE-LOGO");
		if(empty($image))
		{
		    $this->arrViewData["title"] =  $this->objRegistry->Get_Value("SETTING:SITE-NAME");
		}else{
            $this->arrViewData["title"] =  "<img src='/images/{$image}' style='position: relative; top: -2px; width: 200px;' alt='".$this->objRegistry->Get_Value("SETTING:SITE-NAME")."'>";
		}
	}
	
	public function display($PARAMETERS="")
	{
		if($this->objRegistry->Get_Value("IS_LIVE:EDIT_CASHFLOW") == "NO")
		{
			echo "This Page is currently unavailable";
			exit();
		}
	
		$DATA = &$this->arrViewData;
?>
    	<a class="logo" href="<?php echo $this->arrViewData["url"]?>"><?php echo $this->arrViewData["title"]?></a>
 
<?php 	

		return true;
	}	
	
}

