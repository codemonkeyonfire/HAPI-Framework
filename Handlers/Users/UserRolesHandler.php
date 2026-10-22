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

class UserRolesHandler extends BaseHandler
{
    public static $statuses = ["enabled", "disabled"];
    
	public function __construct($DATABASE, &$REGISTRY)
	{
		//  set static settings here 
		$this->DB_TABLE = "UserRoles";
		
		$this->objRegistry = $REGISTRY;
			
		$this->DB_FIELDS[] = array("name" => "UserID",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "AppID",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Role",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "roleID",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Status", 	"type" => "enum|".implode(",", self::$statuses), 		"default" => "'enabled'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "ActiveStartDate",   "type" => "datetime", 		"default" => "'0000-00-00 00:00:00'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "ActiveEndDate",     "type" => "datetime", 		"default" => "'0000-00-00 00:00:00'", 	"comment" => "");
		
		parent::__construct($DATABASE, $REGISTRY);
	}	

	//  *****************************************************************************************************************
	//		CUSTOM LOAD FUNCTIONS
	
	public function Load_Active($USER_ID)
	{
	    $optLIMIT = ($LIMIT>0)?"LIMIT $LAST, $LIMIT":"";

	    
	    $strOPT = "WHERE UserID = {$USER_ID} AND Status = 'enabled' AND (ActiveStartDate = '0000-00-00 00:00:00' OR ActiveStartDate <= now()) AND (ActiveEndDate = '0000-00-00 00:00:00' OR ActiveEndDate > now())";
	    
	    $strSQL = "SELECT * FROM $this->DB_TABLE $strOPT ORDER BY $this->SORT_BY $this->SORT_DIRC $optLIMIT";
	    
	    $this->objDB->Query($strSQL);
	    
	    $this->arrRECORDS = $this->objDB->Fetch_Arrays();
	    
	    $this->numINDEX = 0;
	    $this->numCOUNT = $this->objDB->Get_NumRows();
	    
	    return $this->numCOUNT;
	}	
	
	//  *****************************************************************************************************************
	//		CUSTOM LOAD FUNCTIONS
	
	public function Load_All($ROLE = 'all', $ACTIVE=true, $LIMIT=0, $LAST='')
	{
	    $optLIMIT = ($LIMIT>0)?"LIMIT $LAST, $LIMIT":"";
	    
	    $opts = [];
	    if($ROLE != "all")
	    {
	        $arrTEMP = explode(",", $ROLE);
	        
	        foreach($arrTEMP as $r)
	        {
	            $arrOPT[] = "roleID = '$r'";
	        }
	            
            $opts[] = implode(" OR ", $arrOPT);
	    }
	    
	    if($ACTIVE)
	    {
	        $opts[] = "Status = 'enabled' AND (ActiveStartDate = '0000-00-00 00:00:00' OR ActiveStartDate <= now()) AND (ActiveEndDate = '0000-00-00 00:00:00' OR ActiveEndDate > now())";
	    }
	    
	    if(!empty($opts))
	    {
	        $strOPT = "";
	        $strOPT .= (!empty($opts[0]))?"(".$opts[0].")":"";
	        $strOPT .= (!empty($strOPT))?" AND ":"";
	        $strOPT .= (!empty($opts[1]))?"(".$opts[1].")":"";
	        $strOPT = "WHERE ".$strOPT;
	    }	    
	    
	    $strSQL = "SELECT * FROM $this->DB_TABLE $strOPT ORDER BY $this->SORT_BY $this->SORT_DIRC $optLIMIT";
	    $this->objDB->Query($strSQL);
	    
	    $this->arrRECORDS = $this->objDB->Fetch_Arrays();
	    
	    $this->numINDEX = 0;
	    $this->numCOUNT = $this->objDB->Get_NumRows();
	    
	    return $this->numCOUNT;
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
	
	public function Set_Start_Date($VAL)
	{
	    $parts = explode(" ", $VAL);
	    $this->arrRECORD_WORKBENCH["ActiveStartDate"] = $parts[0];
	    $this->arrRECORD_WORKBENCH["ActiveStartTime"] = $parts[1];
	}
	
	public function Set_End_Date($VAL)
	{
	    $parts = explode(" ", $VAL);
	    $this->arrRECORD_WORKBENCH["ActiveEndDate"] = $parts[0];
	    $this->arrRECORD_WORKBENCH["ActiveEndTime"] = $parts[1];
	}
	
	public function Set_RoleID($VAL)
	{
	    $this->arrRECORD_WORKBENCH["roleID"] = $VAL;
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
	
	public function Get_Start_Date($VAL)
	{
	    $result = $this->arrRECORD_WORKBENCH["ActiveStartDate"]." ".$this->arrRECORD_WORKBENCH["ActiveStartTime"];
	    return $result;
	}
	
	public function Get_End_Date($VAL)
	{
	    $result = $this->arrRECORD_WORKBENCH["ActiveEndDate"]." ".$this->arrRECORD_WORKBENCH["ActiveEndTime"];
	    return $result;
	}
	
	public function Get_Status()
	{
		return $this->arrRECORD_WORKBENCH["Status"];
	}
	
	public function Get_RoleID()
	{
	    return $this->arrRECORD_WORKBENCH["roleID"];
	}
	
//  *****************************************************************************************************************
//	functions not using loaded object

	public function has_one_role($ROLES)
	{
	    foreach($ROLES as $role)
	    {
	        if($this->has_role($role))
	        {
	            return true;
	        }
	    }
	    
	    return false;
	}
	
	public function has_role($ROLE)
	{
	    $user_roles = $this->objRegistry->Get_Value("USER:ROLES");
	    
	    if(in_array($ROLE, $user_roles))
	    {
                return true;
        }
	    
	    return false;
	}
	
	public function fetch_roles()
	{
	    return $this->objRegistry->Get_Value("USER:ROLES");    
	}
}
?>