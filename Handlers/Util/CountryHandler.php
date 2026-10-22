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
namespace Handlers\Util;
use Handlers\General\BaseHandler;

class CountryHandler extends BaseHandler
{
	public function __construct($DATABASE, $SETTINGS="")
	{

		$this->DB_TABLE = "Countries";								// set table here, parent will not set it
		
		$this->DB_FIELDS[] = array("name" => "Name",	"type" => "varchar|45", 	"default" => "", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Code",	"type" => "varchar|45", 	"default" => "", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "ISO3166",	"type" => "char|2", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Display",	"type" => "bool", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Image",	"type" => "varchar|45", 	"default" => "", 	"comment" => "");
				
		parent::__construct($DATABASE, $SETTINGS);
	}
	
//  *****************************************************************************************************************	
//		CUSTOM LOAD FUNCTIONS

//  *****************************************************************************************************************	
//		SET ATTIBUTES	
		
//  *****************************************************************************************************************	
//		GET ATTIBUTES		

	public function Get_Name()
	{
		return $this->arrRECORD_WORKBENCH["Name"];
	}

	public function Get_Code($TYPE='3')
	{
		switch($TYPE)
		{
			case '3':
				$code = $this->arrRECORD_WORKBENCH["Code"];
				break;
			case '2':
				$code = $this->arrRECORD_WORKBENCH["ISO3166"];
				break;
		}
		
		return $code;
	}

	public function Get_Displayable()
	{
		return $this->arrRECORD_WORKBENCH["Display"];
	}

	public function Get_Image()
	{
		return $this->arrRECORD_WORKBENCH["Image"];
	}
	
//  *****************************************************************************************************************	
//		UTILITY FUNCTIONS
	

}
?>
