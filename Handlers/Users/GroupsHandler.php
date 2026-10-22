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
 
class GroupsHandler extends BaseHandler
{
	public static $types = array();
	public static $statuses = array();
	
	public function __construct(&$DATABASE, &$SETTINGS="")
	{
		//  set static settings here 
		$this->DB_TABLE = "groups";

	
		
		parent::__construct($DATABASE, $SETTINGS);			
	}	
	

//  *****************************************************************************************************************	
//		CUSTOM LOAD FUNCTIONS

	
	
//  *****************************************************************************************************************
//		SET ATTIBUTES	

	public function Set_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["name"] = $VAL;
	}	
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	
	
	public function Get_Name()
	{
		return $this->arrRECORD_WORKBENCH["name"];
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
}

?>