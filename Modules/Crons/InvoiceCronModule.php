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
require_once("Modules/AbstractCronModule.php");
require_once("Handlers/Util/h_Messagebox.php");
require_once("Handlers/Util/h_Debug.php");

require_once("Handlers/Invoice/h_Invoice.php");
require_once("Handlers/Invoice/h_InvoiceItem.php");

class InvoiceCronModule extends AbstractCronModule
{
	public function __construct(&$REGISTRY="", &$DATABASE="")
	{
		parent::__construct($REGISTRY, $DATABASE);

		$this->enabled = true;
		$this->set_execute("30 8 * * 1-5");
	}
	
	public function run($PARAMETERS="")
	{
		if(!isset($objInvoice))
			$objInvoice = new InvoiceHandler($this->objRegistry);
				
		$submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
		
		
//####################################################	PROCESS 
			
				
	
		
	}
	
	public function output($PARAMETERS="")
	{
			
		return true;
	}	
	
}

