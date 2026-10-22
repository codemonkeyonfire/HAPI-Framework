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

class SysLogHandler extends BaseHandler
{
	
	public function __construct($DATABASE, $SETTINGS="")
	{
		$this->DB_TABLE = "SysLog";								// set table here, parent will not set it
		
		$this->DB_FIELDS[] = array("name" => "AppID", 					"type" => "integer", 				"default" => "0",	 "comment" => "");
		$this->DB_FIELDS[] = array("name" => "Type", 					"type" => "integer", 				"default" => "0",	 "comment" => "");
		$this->DB_FIELDS[] = array("name" => "Event", 					"type" => "char|45", 				"default" => "''",	 "comment" => "");
		$this->DB_FIELDS[] = array("name" => "Description", 			"type" => "text", 	"default" => "",	 "comment" => "");
		$this->DB_FIELDS[] = array("name" => "UserID", 					"type" => "integer", 				"default" => "0",	 "comment" => "");
		$this->DB_FIELDS[] = array("name" => "created", 			     "type" => "date", 					"default" => "'0000-00-00'",	 "comment" => "");
	

		parent::__construct($DATABASE, $SETTINGS);
	}
	

//  *****************************************************************************************************************
//		SET ATTIBUTES
	
	public function Set_User($VAL)
	{
		$this->arrRECORD_WORKBENCH["UserID"] = $VAL;
	}	

	public function Set_App_ID($VAL)
	{
		$this->arrRECORD_WORKBENCH["AppID"] = $VAL;
	}	
	
	public function Set_Type($VAL)
	{
		$this->arrRECORD_WORKBENCH["Type"] = $VAL;
	}		
	
	public function Set_Event($VAL)
	{
		$this->arrRECORD_WORKBENCH["Event"] = $VAL;
	}	
	
	public function Set_Description($VAL)
	{
		$this->arrRECORD_WORKBENCH["Description"] = $VAL;
	}	

	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	

	public function Get_User()
	{
		return $this->arrRECORD_WORKBENCH["UserID"];
	}	

	public function Get_App_ID()
	{
		return $this->arrRECORD_WORKBENCH["AppID"];
	}	

	public function Get_Type()
	{
		return $this->arrRECORD_WORKBENCH["Type"];
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

	public function fetch_last_log_date($USER, $EVENT)
	{
	    $arrTEMP = array();
	    $strSQL = "SELECT MAX(Created) AS lDate FROM $this->DB_TABLE WHERE UserID = {$USER}";
	    $this->objDB->Query($strSQL);
	    
	    $res = $this->objDB->Fetch_Single();
	    
        return $res->lDate;
	}
	
}

?>
