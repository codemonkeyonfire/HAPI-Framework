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

class AppModulesHandler extends BaseHandler
{
    public static $statuses = ["enabled", "maintainance", "disabled"];
    
    public function __construct($DATABASE)
    {
        //  set static settings here
        $this->DB_TABLE = "AppModules";
        
        $this->DB_FIELDS[] = array("name" => "name",	"type" => "char|50", 	"default" => "''", 	"comment" => "");
        $this->DB_FIELDS[] = array("name" => "status",	"type" => "char|50", 	"default" => "''", 	"comment" => "");
        $this->DB_FIELDS[] = array("name" => "appName",	"type" => "char|100", 	"default" => "''", 	"comment" => "");
        $this->DB_FIELDS[] = array("name" => "appID",	"type" => "integer", 	"default" => "'0'", 	"comment" => "");
        $this->DB_FIELDS[] = array("name" => "viewRoles",   "type" => "char|50", 		"default" => "''", 	"comment" => "");
        
        parent::__construct($DATABASE, $SETTINGS);
    }
    
    //  *****************************************************************************************************************
    //		SET ATTIBUTES
    
    public function Set_Name($VAL)
    {
        $this->arrRECORD_WORKBENCH["name"] = $VAL;
    }
    
    public function Set_App($VAL)
    {
        $this->arrRECORD_WORKBENCH["appName"] = $VAL;
    }
    
    public function Set_Status($VAL)
    {
        $this->arrRECORD_WORKBENCH["status"] = $VAL;
    }
    
    public function Set_App_ID($VAL)
    {
        $this->arrRECORD_WORKBENCH["appID"] = $VAL;
    }
    
    public function Set_Roles_View($VAL)
    {
        $this->arrRECORD_WORKBENCH["viewRoles"] = implode(",", $VAL);
    }
    
    //  *****************************************************************************************************************
    //		GET ATTIBUTES
    public function Get_Name()
    {
        return $this->arrRECORD_WORKBENCH["name"];
    }
    
    public function Get_Status()
    {
        return $this->arrRECORD_WORKBENCH["Status"];
    }
    
    public function Get_App()
    {
        return $this->arrRECORD_WORKBENCH["appName"];
    }
    
    public function Get_App_ID()
    {
        return $this->arrRECORD_WORKBENCH["appID"];
    }
    
    public function Get_Roles_View()
    {
        return explode(",", $this->arrRECORD_WORKBENCH["viewRoles"]);
    }
    
    //  *****************************************************************************************************************
    //	functions not using loaded object
    
    
    
}
?>