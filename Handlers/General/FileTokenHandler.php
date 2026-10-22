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

class FileTokenHandler extends BaseHandler
{
	public function __construct($DATABASE, $SETTINGS="")
	{
		//  set static settings here 
		$this->DB_TABLE = "FileTokens";
		
		$this->DB_FIELDS[] = array("name" => "SessionID",	"type" => "char|100", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "PublicName",	"type" => "char|65", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "PrivateName",	"type" => "char|100", 	"default" => "''", 	"comment" => "");

				
		parent::__construct($DATABASE, $SETTINGS);			
	}	
	

//  *****************************************************************************************************************
//		SET ATTIBUTES

	public function Set_ID($VAL)
	{
		$this->arrRECORD_WORKBENCH["ID"] = $VAL;
	}	
	
	public function Set_Session($VAL)
	{
		$this->arrRECORD_WORKBENCH["SessionID"] = $VAL;
	}	

	public function Set_Public_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["PublicName"] = $VAL;
	}	

	public function Set_Private_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["PrivateName"] = $VAL;
	}		
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	
	public function Get_ID()
	{
		return $this->arrRECORD_WORKBENCH["ID"];
	}	

	public function Get_Session()
	{
		return $this->arrRECORD_WORKBENCH["SessionID"];
	}

	public function Get_Public_Name()
	{
		return $this->arrRECORD_WORKBENCH["PublicName"];
	}
	
	public function Get_Private_Name()
	{
		return $this->arrRECORD_WORKBENCH["PrivateName"];
	}	

	public function Get_Created()
	{
		return $this->arrRECORD_WORKBENCH["Created"];
	}	

//  *****************************************************************************************************************
//	functions not using loaded object
	
	public function Create($SESSION, $FILEPATH)
	{
		$this->Clean_Tokens();
		
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		
		srand($seed);
		$r1 = substr($SESSION, rand(0, 15), 4);		
		$r2 = substr($SESSION, rand(10, 21), 4);	
		
		$token = md5($r1.$r2);
		
		$this->Set_ID(0);
		$this->Set_Session($SESSION);
		$this->Set_Private_Name($FILEPATH);
		$this->Set_Public_Name($token);
		
		$this->Commit();
		
		return $token;
	}
	
	public function Clean_Tokens()
	{
		$strSQL = "DELETE FROM $this->DB_TABLE WHERE Created < DATE_SUB(NOW(), INTERVAL 4 HOUR)";
		$this->objDB->Query($strSQL);		
	}
}
?>