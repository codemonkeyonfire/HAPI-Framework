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
namespace Handlers\Users;
use Handlers\General\BaseHandler;

class UserAppsHandler extends BaseHandler
{
	public function __construct($DATABASE)
	{
		//  set static settings here 
		$this->DB_TABLE = "Validation";
			
		$this->DB_FIELDS[] = array("name" => "UserID",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "AppID",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Status",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		
		parent::__construct($DATABASE, $SETTINGS);
	}	

//  *****************************************************************************************************************
//		SET ATTIBUTES

	public function Set_User($VAL)
	{
		$this->arrRECORD_WORKBENCH["UserID"] = $VAL;
	}	

	public function Set_App($VAL)
	{
		$this->arrRECORD_WORKBENCH["AppID"] = $VAL;
	}	

	public function Set_Status($VAL)
	{
		$this->arrRECORD_WORKBENCH["Status"] = $VAL;
	}
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	
	public function Get_User()
	{
		return $this->arrRECORD_WORKBENCH["UserID"];
	}	

	public function Get_App()
	{
		return $this->arrRECORD_WORKBENCH["AppID"];
	}	

	public function Get_Code()
	{
		return $this->arrRECORD_WORKBENCH["Code"];
	}

	public function Get_Status()
	{
		return $this->arrRECORD_WORKBENCH["Status"];
	}

//  *****************************************************************************************************************
//	functions not using loaded object
	
}
?>