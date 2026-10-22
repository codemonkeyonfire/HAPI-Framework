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

class ContactHandler extends BaseHandler
{
	public function __construct($DATABASE, $SETTINGS="")
	{
		$this->DB_TABLE = "Contacts";								// set table here, parent will not set it
		
		$this->DB_FIELDS[] = array("name" => "OwnerID",		"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "EntityID",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "FirstName",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "LastName",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Username",	"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Email",		"type" => "char|100", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Address",		"type" => "char|150", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "City",		"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "State",		"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "CountryID",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Zip",			"type" => "char|10", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Phone",		"type" => "char|20", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Fax",			"type" => "char|30", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Birthday",	"type" => "datetime", 	"default" => "'0000-00-00 00:00:00'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Title",		"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Type",		"type" => "char|45", 	"default" => "''", 	"comment" => "");
		
		$this->SORT_BY = "LastName";
		$this->SORT_DIRC = "ASC";		
		parent::__construct($DATABASE, $SETTINGS);
	}	
	
//  *****************************************************************************************************************	
//		CUSTOM LOAD FUNCTIONS

	
//  *****************************************************************************************************************
//		SET ATTIBUTES

	public function Set_First_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["FirstName"] = $VAL;
	}	
	
	public function Set_Last_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["LastName"] = $VAL;
	}		
	
	public function Set_User_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["Username"] = $VAL;
	}	

	public function Set_Email($VAL)
	{
		$this->arrRECORD_WORKBENCH["Email"] = $VAL;
	}			
	
	public function Set_Address($VAL)
	{
		$this->arrRECORD_WORKBENCH["Address"] = $VAL;
	}		

	public function Set_City($VAL)
	{
		$this->arrRECORD_WORKBENCH["City"] = $VAL;
	}		

	public function Set_State($VAL)
	{
		$this->arrRECORD_WORKBENCH["State"] = $VAL;
	}		
	
	public function Set_Country_ID($VAL)
	{
		$this->arrRECORD_WORKBENCH["CountryID"] = $VAL;
	}		

	public function Set_Zip($VAL)
	{
		$this->arrRECORD_WORKBENCH["Zip"] = $VAL;
	}		

	public function Set_Type($VAL)
	{
		$this->arrRECORD_WORKBENCH["Type"] = $VAL;
	}		

	public function Set_Phone($VAL)
	{
		$this->arrRECORD_WORKBENCH["Phone"] = $VAL;
	}		

	public function Set_Fax($VAL)
	{
		$this->arrRECORD_WORKBENCH["Fax"] = $VAL;
	}				
	
	public function Set_Birthday($VAL)
	{
		$this->arrRECORD_WORKBENCH["Birthday"] = $VAL;
	}		
	
	public function Set_Title($VAL)
	{
		$this->arrRECORD_WORKBENCH["Title"] = $VAL;
	}		
	
	public function Set_Entity($VAL)
	{
		$this->arrRECORD_WORKBENCH["EntityID"] = $VAL;
	}		
		
	public function Set_Owner($VAL)
	{
		$this->arrRECORD_WORKBENCH["OwnerID"] = $VAL;
	}	
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	

	public function Get_First_Name()
	{
		return $this->arrRECORD_WORKBENCH["FirstName"];
	}	
	
	public function Get_Last_Name()
	{
		return $this->arrRECORD_WORKBENCH["LastName"];
	}		
	
	public function Get_Email()
	{
		return $this->arrRECORD_WORKBENCH["Email"];
	}		

	public function Get_Address()
	{
		return $this->arrRECORD_WORKBENCH["Address"];
	}		

	public function Get_City()
	{
		return $this->arrRECORD_WORKBENCH["City"];
	}	
	
	public function Get_State()
	{
		return $this->arrRECORD_WORKBENCH["State"];
	}			
	
	public function Get_Country_ID()
	{
		return $this->arrRECORD_WORKBENCH["CountryID"];
	}	
		
	public function Get_Zip()
	{
		return $this->arrRECORD_WORKBENCH["Zip"];
	}	
	
	public function Get_Type()
	{
		return $this->arrRECORD_WORKBENCH["Type"];
	}				
	
	public function Get_Phone()
	{
		return $this->arrRECORD_WORKBENCH["Phone"];
	}		

	public function Get_Fax()
	{
		return $this->arrRECORD_WORKBENCH["Fax"];
	}			

	public function Get_Title()
	{
		return $this->arrRECORD_WORKBENCH["Title"];
	}	
	
	public function Get_Birthday()
	{
		return $this->arrRECORD_WORKBENCH["Birthday"];
	}	
		
	public function Get_First_Last_Name()
	{
		return $this->Get_First_Name()." ".strtoupper(substr($this->Get_Last_Name(), 0, 1));	
	}
		
	public function Get_Entity()
	{
		return $this->arrRECORD_WORKBENCH["EntityID"];	
	}	

	public function Get_Owner()
	{
		return $this->arrRECORD_WORKBENCH["OwnerID"];	
	}	
	
//  *****************************************************************************************************************
	
}

?>
