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

class AppsHandler extends BaseHandler
{
    public static $statuses = ["enabled", "maintainance", "disabled"];
    
	public function __construct($DATABASE)
	{
		//  set static settings here 
		$this->DB_TABLE = "Apps";
			
		$this->DB_FIELDS[] = array("name" => "Name",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Status",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "UrlTag",	"type" => "char|100", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Type",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "viewRoles",   "type" => "char|50", 		"default" => "''", 	"comment" => "");
		
		parent::__construct($DATABASE, $SETTINGS);
	}	

//  *****************************************************************************************************************
//		SET ATTIBUTES

	public function Set_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["Name"] = $VAL;
	}	
	
	public function Set_Module($VAL)
	{
	    $this->arrRECORD_WORKBENCH["module"] = $VAL;
	}
	
	public function Set_Status($VAL)
	{
		$this->arrRECORD_WORKBENCH["Status"] = $VAL;
	}	

	public function Set_UrlTag($VAL)
	{
		$this->arrRECORD_WORKBENCH["UrlTag"] = $VAL;
	}	

	public function Set_Type($VAL)
	{
		$this->arrRECORD_WORKBENCH["Type"] = $VAL;
	}
	
	public function Set_Roles_View($VAL)
	{
	    $this->arrRECORD_WORKBENCH["viewRoles"] = implode(",", $VAL);
	}
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	
	public function Get_Name()
	{
		return $this->arrRECORD_WORKBENCH["Name"];
	}	

	public function Get_Status()
	{
		return $this->arrRECORD_WORKBENCH["Status"];
	}	

	public function Get_UrlTag()
	{
		return $this->arrRECORD_WORKBENCH["UrlTag"];
	}	

	public function Get_Type()
	{
		return $this->arrRECORD_WORKBENCH["Type"];
	}
	
	public function Get_Roles_View()
	{
	    return explode(",", $this->arrRECORD_WORKBENCH["viewRoles"]);
	}
	
//  *****************************************************************************************************************
//	functions not using loaded object


	
}
?>