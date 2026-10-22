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

class LogoutModule extends AbstractModule 
{
	public function run($PARAMETERS="")
	{
	    $this->template = "oneColumnCentered";
	    
	    session_destroy();
	    
	    if(!isset($objURL))	$objURL = new \Handlers\Util\UrlHandler($this->objRegistry);
		$REQUEST_DATA = $this->objRegistry->Get_Value("REQUEST:DATA");
		
		$this->arrFormData["message"]  = "You have been logged out";
		$this->arrFormData["url"] = $objURL->Make("User", "LogIn");

	}
	
	public function display($PARAMETERS="")
	{
		$DATA = &$this->arrFormData;
?>
		<h1><?php echo $DATA["message"]?></h1>
		<script>document.location.href="<?php echo $DATA["url"]?>";</script>
<?php 
		return true;
	}
	
}


	
