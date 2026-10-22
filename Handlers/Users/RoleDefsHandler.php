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

class RoleDefsHandler extends BaseHandler
{
    public static $statuses = ["enabled", "disabled"];
    private $roleNames;
    private $roleIDs;
    
	public function __construct($DATABASE, $REGISTRY)
	{
		//  set static settings here 
		$this->DB_TABLE = "RoleDefinitions";
		
		$this->objRegistry = $REGISTRY;
			
		$this->DB_FIELDS[] = array("name" => "name",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "createdby",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "displayOrder",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "status", 	"type" => "enum|".implode(",", ["enabled", "disabled"]), 		"default" => "'enabled'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "type",   "type" => "char|45", 		"default" => "'user'", 	"comment" => "");
		
		parent::__construct($DATABASE, $SETTINGS);
		
		$strSQL = "SELECT * FROM $this->DB_TABLE";
		$this->objDB->Query($strSQL);
		
		$arrTEMP = $this->objDB->Fetch_Arrays();
		
		foreach($arrTEMP as $role)
		{
		    $this->roleNames[$role["ID"]] = $role["name"];
		    $this->roleIDs[$role["name"]] = $role["ID"];
		}

	}	

//  *****************************************************************************************************************
//		SET ATTIBUTES

	public function Set_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["name"] = $VAL;
	}	

	public function Set_Created_By($VAL)
	{
		$this->arrRECORD_WORKBENCH["createdby"] = $VAL;
	}	

	public function Set_Status($VAL)
	{
		$this->arrRECORD_WORKBENCH["status"] = $VAL;
	}
	
	public function Set_Type($VAL)
	{
	    $this->arrRECORD_WORKBENCH["type"] = $VAL;
	}
	
	public function Set_Display_Order($VAL)
	{
	    $this->arrRECORD_WORKBENCH["displayOrder"] = $VAL;
	}
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	
	public function Get_Name($ID="")
	{
	    if($ID=="")
	    {
		    return $this->arrRECORD_WORKBENCH["name"];
	    }else{
	        return $this->roleNames[$ID];
	    }
	}	

	public function Get_Created_By()
	{
		return $this->arrRECORD_WORKBENCH["createby"];
	}	
	
	public function Get_Type($VAL)
	{
	    return $this->arrRECORD_WORKBENCH["type"];
	}
	
	public function Get_Status()
	{
		return $this->arrRECORD_WORKBENCH["status"];
	}
	
	public function Get_Display_Order()
	{
	    return $this->arrRECORD_WORKBENCH["displayOrder"];
	}
	
	public function Get_ID($NAME="")
	{
	    if($NAME != "")
	    {
	       return $this->roleIDs[$NAME];
	    }
	    
	    return parent::Get_ID();
	}
	
//  *****************************************************************************************************************
//	functions not using loaded object

}
?>