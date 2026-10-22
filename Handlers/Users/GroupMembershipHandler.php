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
 
class GroupMembershipHandler extends BaseHandler
{
	public static $access = array("view", "upload", "admin");
	public static $types = array("family", "extFamily", "friends");
	
	public function __construct(&$DATABASE, &$SETTINGS="")
	{
		//  set static settings here 
		$this->DB_TABLE = "group_membership";
	
		parent::__construct($DATABASE, $SETTINGS);			
	}	
	

//  *****************************************************************************************************************	
//		CUSTOM LOAD FUNCTIONS
	public function Load_Members($GROUP_ID)
	{
		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE groupID = $GROUP_ID";
		$this->objDB->Query($strSQL);
	
		$this->arrRECORDS = $this->objDB->Fetch_Arrays();
	
		$this->arrRECORD_WORKBENCH = $this->arrRECORDS[0];
	
		$this->numINDEX = 0;
		$this->numCOUNT = count($this->arrRECORDS);
	
		$this->STATE = "loaded";
	
		//print_r($this->arrRECORD_WORKBENCH);
		//die();
	}
	
	
//  *****************************************************************************************************************
//		SET ATTIBUTES	

	public function Set_User($VAL)
	{
		$this->arrRECORD_WORKBENCH["userID"] = $VAL;
	}	

	public function Set_Group($VAL)
	{
		$this->arrRECORD_WORKBENCH["groupID"] = $VAL;
	}

	public function Set_Acess($VAL)
	{
		$this->arrRECORD_WORKBENCH["access"] = $VAL;
	}

	public function Set_Type($VAL)
	{
		$this->arrRECORD_WORKBENCH["groupType"] = $VAL;
	}

	public function Set_Start($VAL)
	{
		$this->arrRECORD_WORKBENCH["start"] = $VAL;
	}

	public function Set_End($VAL)
	{
		$this->arrRECORD_WORKBENCH["end"] = $VAL;
	}
		
//  *****************************************************************************************************************	
//		GET ATTIBUTES	
	
	public function Get_User()
	{
		return $this->arrRECORD_WORKBENCH["userID"];
	}	

	public function Get_Group()
	{
		return $this->arrRECORD_WORKBENCH["groupID"];
	}

	public function Get_Access()
	{
		return $this->arrRECORD_WORKBENCH["access"];
	}

	public function Get_Type()
	{
		return $this->arrRECORD_WORKBENCH["groupType"];
	}

	public function Get_Start()
	{
		return $this->arrRECORD_WORKBENCH["start"];
	}

	public function Get_End()
	{
		return $this->arrRECORD_WORKBENCH["end"];
	}
	
//  *****************************************************************************************************************
//	functions not using loaded object	

	public function list_User_Groups($USER_ID)
	{
		$sql = "SELECT groupID FROM {$this->DB_TABLE} WHERE UserID = $USER_ID";
		$this->objDB->Query($sql);
		
		while($data = $this->objDB->Fetch_Single())
		{
			$arrRES[] = $data->groupID;
		}
		
		return $arrRES;	
		
	}
	
	static public function List_Types()
	{
		return self::$types;
	}
	
	static public function List_Access()
	{
		return self::$access;
	}
}

?>