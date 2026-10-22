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
 
namespace Handlers\General;

class SettingsHandler extends BaseHandler
{
	
	public function __construct($DATABASE, $SETTINGS="")
	{
		$this->DB_TABLE = "Settings";								// set table here, parent will not set it
		
		$this->DB_FIELDS[] = array("name" => "Name", 					"type" => "char|45", 				"default" => "''",	 "comment" => "");
		$this->DB_FIELDS[] = array("name" => "Value", 					"type" => "char|45", 				"default" => "''",	 "comment" => "");
		$this->DB_FIELDS[] = array("name" => "modified", 					"type" => "datetime", 				"default" => "'0000-00-00 00:00:00'",	 "comment" => "");
	

		parent::__construct($DATABASE, $SETTINGS);
	}
	

//  *****************************************************************************************************************
//		SET ATTIBUTES
	
	public function Set_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["Name"] = $VAL;
	}	

	public function Set_Setting_Value($VAL)
	{
		$this->arrRECORD_WORKBENCH["Value"] = $VAL;
	}	
	
	public function Set_Modified($VAL)
	{
		$this->arrRECORD_WORKBENCH["modified"] = $VAL;
	}		
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	

	public function Get_Name()
	{
		return $this->arrRECORD_WORKBENCH["Name"];
	}	

	public function Get_Setting_Value()
	{
		return $this->arrRECORD_WORKBENCH["Value"];
	}	

	public function Get_Modified()
	{
		return $this->arrRECORD_WORKBENCH["modified"];
	}	

	public function Get_Event()
	{
		return $this->arrRECORD_WORKBENCH["Event"];
	}
		
		
	public function Get_Description()
	{
		return $this->arrRECORD_WORKBENCH["Description"];
	}		
	

//  *****************************************************************************************************************

	public function fetch_value($KEY)
	{

	    $strSQL = "SELECT Value FROM $this->DB_TABLE WHERE Name = '{$KEY}'";
	    $this->objDB->Query($strSQL);
	    
	    $res = $this->objDB->Fetch_Single();
	    
        return $res->Value;
	}
	
}

?>
