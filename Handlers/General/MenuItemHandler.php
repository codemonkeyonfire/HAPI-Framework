<?php
/**
 * Handler of dynamic menu system
 * 
 *
 * 
 * can only implement drivers
 * can only use other handlers if they are passed by reference
 * Do not reference Globals except for lib paths (explicitly pass values to functions)
 * can implement base class if and only if base class used by more than one handlers
*
* @author 
* @version 1.0
* @package insideArtCenter
* @subpackage common
* @date 6/14/12
*/
namespace Handlers\General;

class MenuItemHandler extends BaseHandler
{
    public static $statuses = ["disabled", "active", "hidden"];
    public static $types = ["label", "link", "image", "hr", "space", "feed", "extLink"];
    
	public function __construct(&$DATABASES)
	{
		$this->count = 0;
			
		//  set static settings here
		$this->DB_TABLE = "MenuItems";
		
		$this->DB_FIELDS[] = array("name" => "name",	"type" => "char|50", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "menu",	"type" => "char|50", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "status", 	"type" => "enum|".implode(",", self::$statuses), 		"default" => "'hidden'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "displayText",	"type" => "char|100", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "linkID",	"type" => "integer", 	"default" => "'0'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "parentItemID",	"type" => "integer", 	"default" => "'0'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "displayOrder",	"type" => "integer", 	"default" => "'0'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "viewRoles",   "type" => "char|50", 		"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "description",   "type" => "text", 		"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "action",   "type" => "text", 		"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "searchable",   "type" => "bool", 		"default" => "''", 	"comment" => "");
		
		parent::__construct($DATABASES);
	}	
		
	public function Load_Menu($ID, $PARENT_ID=0)
	{
	    $strSQL = "SELECT * FROM {$this->DB_TABLE} WHERE menu = '{$ID}' AND parentItemID = {$PARENT_ID} ORDER BY displayOrder";
	    $this->objDB->Query($strSQL);
	    
	    $arrTEMP = $this->objDB->Fetch_Arrays();
	    
	    $this->arrRECORDS = $arrTEMP;
	    $this->arrRECORD_WORKBENCH = $arrTEMP[0];
	    
	    $this->numINDEX = 0;
	    $this->numCOUNT = count($arrTEMP);
	    
	    $this->STATE = "loaded";
	    
	    return $this->objDB->Get_NumRows();
	}	


//#####################################################################################################################	
	public function Get_Menu()
	{
		return $this->Get_Value("menu");
	}

	public function Get_Parent_ID()
	{
		return $this->Get_Value("parentItemID");
	}

	public function Get_Text()
	{
	    if($this->Get_Value("displayText") == "" && $this->Get_Link_ID() > 0)
	    {
	        $this->Fetch_Link();
	        return $this->Get_Value("linkText");
	    }
	        
		return $this->Get_Value("displayText");
	}	
	
	public function Get_Type()
	{
		return $this->Get_Value("type");
	}
	
	public function Get_Status()
	{
		return $this->Get_Value("status");
	}
	
	public function Get_Roles_View()
	{
	    return explode(",", $this->Get_Value("viewRoles"));
	}
	
	public function Get_Action($AUTO_FALLBACK=true)
	{
	    if($this->Get_Value("action") == "" && $this->Get_Link_ID() > 0 && $AUTO_FALLBACK)
	    {
	        $this->Fetch_Link();
	        return $this->Get_Value("linkUrl");
	    }
	    
		return $this->Get_Value("action");
	}

	public function Get_Display_Order()
	{
		return $this->Get_Value("displayOrder");
	}	

	public function Get_Name()
	{
		return $this->Get_Value("name");
	}	

	public function Get_Description()
	{
		return $this->Get_Value("description");
	}	

	public function Get_Searchable()
	{
		return $this->Get_Value("searchable");
	}	
	
	public function Get_Link_ID()
	{
	    return $this->Get_Value("linkID");
	}	
//#####################################################################################################################	

	public function Set_Menu($VALUE)
	{
		$this->Set_Value("menu", $VALUE);
	}	
	
	public function Set_Parent_ID($VALUE)
	{
		$this->Set_Value("parentItemID", $VALUE);
	}	
	
	public function Set_Text($VALUE)
	{
		$this->Set_Value("displayText", $VALUE);
	}	
	
	public function Set_Type($VALUE)
	{
		$this->Set_Value("type", $VALUE);
	}	
	
	public function Set_Status($VALUE)
	{
		$this->Set_Value("status", $VALUE);
	}	
	
	public function Set_Roles_View($VAL)
	{
	    $this->Set_Value("viewRoles", implode(",", $VAL));
	}
	
	public function Set_Action($VALUE)
	{
		$this->Set_Value("action", $VALUE);
	}	
	
	public function Set_Display_Order($VALUE)
	{
		$this->Set_Value("displayOrder", $VALUE);
	}	
	
	public function Set_Name($VALUE)
	{
		$this->Set_Value("name", $VALUE);
	}	
	
	public function Set_Description($VALUE)
	{
		$this->Set_Value("description", $VALUE);
	}	
	
	public function Set_Searchable($VALUE)
	{
		$this->Set_Value("searchable", $VALUE);
	}	
	
	public function Set_Link_ID($VALUE)
	{
	    $this->Set_Value("linkID", $VALUE);
	}

//  *****************************************************************************************************************
	//	functions not using loaded object
	
	static public function List_Types()
	{
	    return self::$types;
	}
	
	static public function List_Statuses()
	{
	    return self::$statuses;
	}
	
//#####################################################################################################################	

	public function List_Menus()
	{
	    $strSQL = "SELECT menu AS name, count(*) AS itemCount FROM {$this->DB_TABLE} GROUP BY menu";
		$this->objDB->Query($strSQL);
		$buffer = $this->objDB->Fetch_Arrays();
				
		return $buffer;
	}
	
	public function List_Siblings($PARENT_ID="", $MENU_ID="")
	{
		$itemID = ($PARENT_ID == "")?$this->Get_Parent_ID():$PARENT_ID;
		$menuID = ($MENU_ID == "")?$this->Get_Menu():$MENU_ID;
		
		$strSQL = "SELECT ID, displayText, linkID FROM {$this->DB_TABLE} WHERE parentItemID = {$itemID}";
		
		if($itemID == 0)
		{
		    $strSQL .= " AND menu = '{$menuID}'";
		}
			
		$strSQL .= " ORDER BY displayOrder";
		
		$this->objDB->Query($strSQL);
		$buffer = $this->objDB->Fetch_Arrays();
				
		return $buffer;
	}
	
	public function Generate_Path($PARENT_ID="", $PATH="")
	{
		$itemID = ($PARENT_ID == "")?$this->Get_Parent_ID():$PARENT_ID;
		$PATH = [];
		if($itemID != 0)
		{		
			
		    $strSQL = "SELECT a.ID, a.displayText AS itemText, parentItemID, linkID, b.displayText AS linkText, b.url AS url FROM {$this->DB_TABLE} a LEFT JOIN Menulinks b ON a.linkID = b.ID WHERE a.ID = {$itemID}";
			$this->objDB->Query($strSQL);
			$buffer = $this->objDB->Fetch_Arrays();
			
			$text = $buffer[0]["itemText"];
			if(empty($buffer[0]["itemText"]) && $buffer[0]["linkID"] > 0)
			{
			    $text = $buffer[0]["linkText"];
			}
							
			$PATH = [];
			$PATH = $this->Generate_Path($buffer[0]["parentItemID"], $PATH);
			$PATH[] = array($buffer[0]["ID"], $text, $buffer[0]["linkID"]);
		}
		
		return $PATH;
	}
	
	public function Remove_From_Order($SPOT="", $PARENT_ID="", $MENU_ID="")
	{
		$spot = ($SPOT == "")?$this->Get_Display_Order():$SPOT;
		$parentID = ($PARENT_ID == "")?$this->Get_Parent_ID():$PARENT_ID;
		$menuID = ($MENU_ID == "")?$this->Get_Menu():$MENU_ID;
		
		$strSQL = "UPDATE {$this->DB_TABLE} SET displayOrder = displayOrder - 1 WHERE displayOrder > {$spot} AND parentItemID = '{$parentID}' AND menu = '{$menuID}'";	
		$this->objDB->Query($strSQL);
	}
	
	public function Insert_Into_Order($SPOT_NUMBER, $PARENT_ID="", $MENU_ID="")
	{
		$parentID = ($PARENT_ID == "")?$this->Get_Parent_ID():$PARENT_ID;
		$menuID = ($MENU_ID == "")?$this->Get_Menu():$MENU_ID;
		
		$strSQL = "UPDATE {$this->DB_TABLE} SET displayOrder = displayOrder + 1 WHERE displayOrder >= {$SPOT_NUMBER} AND parentItemID = '{$parentID}' AND menu = '{$menuID}'";	
		$this->objDB->Query($strSQL);
		
		$strSQL = "UPDATE {$this->DB_TABLE} SET displayOrder = {$SPOT_NUMBER} WHERE ID = '{$this->Get_ID()}'";	
		$this->objDB->Query($strSQL);
	}
	
	public function Count($MENU_ID)
	{
	    $strSQL = "SELECT count(*) AS cnt FROM {$this->DB_TABLE} WHERE menu = '{$MENU_ID}'";
	    $this->objDB->Query($strSQL);
	    $buffer = $this->objDB->Fetch_Arrays();
	    
	    return $buffer[0]["cnt"];
	}
	
	public function Fetch_Roles($ITEM_ID="")
	{
		$itemID = ($ITEM_ID == "")?$this->Get_ID():$ITEM_ID;
		$strSQL = "SELECT * FROM {$this->DB_TABLE}_roles_v WHERE itemID = {$itemID}";		
		$this->objDB->Query($strSQL);
		$buffer = $this->objDB->Fetch_Arrays();
		
		return $buffer;
	}
	
	public function Fetch_Link()
	{
	    if(empty($this->Get_Value("linkText")) || empty($this->Get_Value("linkUrl")))
	    {
	        $strSQL = "SELECT * FROM menu_links WHERE ID = ".$this->Get_Link_ID();
	       $this->objDB->Query($strSQL);
	       $buffer = $this->objDB->Fetch_Arrays();
	    
	       $this->Set_Value("linkText", $buffer[0]["displayText"]);
	       $this->Set_Value("linkUrl", $buffer[0]["url"]);
	    }
	}
	
	public function Add_Role($ROLE_ID, $CREATED_BY, $ITEM_ID="")
	{
		$itemID = ($ITEM_ID == "")?$this->Get_ID():$ITEM_ID;
		
		$strSQL = "INSERT {$this->DB_TABLE}_roles (itemID, roleID, createdBy, created) VALUES ({$itemID}, {$ROLE_ID}, '{$CREATED_BY}', now())";	
		$this->objDB->Query($strSQL);
		
	}
	
	public function Remove_Role($ROLE_ID, $ITEM_ID="")
	{
		$itemID = ($ITEM_ID == "")?$this->Get_ID():$ITEM_ID;
		$strSQL = "DELETE FROM {$this->DB_TABLE}_roles WHERE itemID = {$itemID} AND roleID = {$ROLE_ID}";	
		$this->objDB->Query($strSQL);
	}
	
	public function Clear_Roles($ITEM_ID="")
	{
		$itemID = ($ITEM_ID == "")?$this->Get_ID():$ITEM_ID;
		$strSQL = "DELETE FROM {$this->DB_TABLE}_roles WHERE itemID = {$itemID}";	
		$this->objDB->Query($strSQL);
	}

	public function Hide_link($NAME)
	{
	    $strSQL = "UPDATE {$this->DB_TABLE} SET status = 'hidden' WHERE name = '{$NAME}'";
	    $this->objDB->Query($strSQL);
	}
	
	public function Show_link($NAME)
	{
	    $strSQL = "UPDATE {$this->DB_TABLE} SET status = 'active' WHERE name = '{$NAME}'";
	    $this->objDB->Query($strSQL);
	}
}