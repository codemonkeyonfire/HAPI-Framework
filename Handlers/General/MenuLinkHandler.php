<?php
/**
 * Template file for creating new handlers files
 * 
 *
 * 
 * can only implement drivers
 * can only use other handlers if they are passed by reference
 * Do not reference Globals except for lib paths (explicitly pass values to functions)
 * can implement base class if and only if base class used by more than one handlers
 * only more then one tables when they are same logical data but different sources and do not have a handler of their own
*
* @author 
* @version 1.0
* @package insideArtCenter
* @subpackage common
* @date 6/14/12
*/
namespace Handlers\General;

class MenuLinkHandler extends BaseHandler
{
		
	public function __construct(&$DATABASES, &$REGISTRY)
	{
	    $this->DB_TABLE = "MenuLinks";
	    
	    $this->DB_FIELDS[] = array("name" => "displayText",	"type" => "char|100", 	"default" => "''", 	"comment" => "");
	    $this->DB_FIELDS[] = array("name" => "owner",	"type" => "integer", 	"default" => "'0'", 	"comment" => "");
	    $this->DB_FIELDS[] = array("name" => "url",   "type" => "char|100", 		"default" => "''", 	"comment" => "");
	    
	    parent::__construct($DATABASES);		
	}	
				
//#####################################################################################################################	
	
	public function Get_Url()
	{
		return $this->Get_Value("url");
	}
	
	public function Get_Display_Text()
	{
	    return $this->Get_Value("displayText");
	}	
	
	public function Get_Owner()
	{
	    return $this->Get_Value("owner");
	}
	
//#####################################################################################################################	

	public function Set_Url($VALUE)
	{
		$this->Set_Value("url", $VALUE);
	}	
		
	public function Set_Text($VALUE)
	{
	    $this->Set_Value("displayText", $VALUE);
	}
	
	public function Set_Owner($VALUE)
	{
	    $this->Set_Value("owner", $VALUE);
	}
	
//#####################################################################################################################	
	
}