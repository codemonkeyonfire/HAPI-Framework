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

class RegistryHandler
{
	private $drvMEMORY;
	private $drvDATABASE;
	private $drvCOOKIE;
	
	private $db_table;
	private $values_in_db;

	public function __construct(&$DATABASE, $MODE = 'live')
	{
		//  set static settings here 
		$this->db_table = "Settings";
		
		$this->drvMEMORY = new \Drivers\MemArrDriver();
	
	
		// Load Dynamic Settings from DATABASE
		$arrDATABASE = array();
		

		$this->drvCOOKIE = new \Drivers\CookieDriver();
		$this->drvDATABASE = &$DATABASE;
		
		// Load Dynamic Settings from DATABASE
		$arrDATABASE = array();
			
		$this->Load_From_DB();			
	}	
	
	public function Load_From_File($FILEPATH="site.ini")
	{
		$lines = file($FILEPATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line)
		{
			list($Key, $Val) = explode("=", $line);
			$this->drvMEMORY->Set_Value($Key, $Val);
		}
			
	}
	
	public function Get_Value($NAME = "SITE:NAME")
	{
			list($CATEGORY, $SETTING) = explode(":", $NAME);
			//   reference of values by category:setting pair
			switch($CATEGORY)
			{
				case "COOKIE":
					return $this->drvCOOKIE->Get_Value($SETTING);
					break;
				case "SESSION":
					return $_SESSION[$SETTING];
					break;
				default:
					return $this->drvMEMORY->Get_Value($NAME);
			}
	}
	
	public function Set_Value($NAME, $VALUE)
	{
			//   reference of values by category:setting pair
			//	creates temp config setting if does not exist
			
			list($CATEGORY, $SETTING) = explode(":", $NAME);
			
			switch($CATEGORY)
			{
				case "COOKIE":
					$this->objCOOKIE->Set_Value($SETTING, $VALUE);
					break;
				case "SESSION":
					$_SESSION[$SETTING] = $VALUE;
					break;
				case "SETTING":
				    $strSQL = "INSERT INTO {$this->db_table} (Key, Value) VALUES ('{$SETTING}', '{$VALUE}') ON DUPLICATE KEY UPDATE Value = '{$VALUE}' ";
				    $this->drvDATABASE->Query($strSQL);	
				default:
					$this->drvMEMORY->Set_Value($NAME, $VALUE);
			}
	}		

	public function Set_NonExpiring_Cookie($NAME, $VALUE)
	{
		$this->drvCOOKIE->Set_Cookie($NAME, $VALUE, "", "/");
	}

	public function Load_From_DB($SITE_ID="")
	{
		$strSQL = "SELECT * FROM {$this->db_table}";
		$this->drvDATABASE->Query($strSQL);
		
		$settings = $this->drvDATABASE->Fetch_Arrays();
		
		foreach($settings as $set)
		{
			$this->drvMEMORY->Set_Value("SETTING:".$set["Key"], $set["Value"]);
		}

	}		
	
	public function Add_JS($SCRIPT, $LOCATION="footer")
	{
		$arr = $this->drvMEMORY->Get_Value("JAVASCRIPT:INCLUDE@".strtoupper($LOCATION));
		
		if(empty($arr) || !in_array($SCRIPT, $arr))
		{
		  $arr[] = $SCRIPT;
		  $this->drvMEMORY->Set_Value("JAVASCRIPT:INCLUDE@".strtoupper($LOCATION), $arr);
		}
		
		return count($arr);
	}
	
	public function Add_CSS($SCRIPT)
	{
		$arr = $this->drvMEMORY->Get_Value("CSS:INCLUDE");
		if(empty($arr) || !in_array($SCRIPT, $arr))
		{
		  $arr[] = $SCRIPT;
		  $this->drvMEMORY->Set_Value("CSS:INCLUDE", $arr);
		}
	
		return count($arr);
	}	
	
	
	public function Clear_Menu($MENU)
	{
	    $this->drvMEMORY->Set_Value("MENU:{$MENU}", []);
	}
	
	public function Add_Menu_Item($MENU, $ITEM_TEXT, $ITEM_URL, $ITEM_TYPE="link", $ICON="", $AUX_PROPS="")
	{
	    $arr = [];
	    $arr = $this->drvMEMORY->Get_Value("MENU:{$MENU}");
	    $buffer = ["title" => $ITEM_TEXT, "link" => $ITEM_URL, "type" => $ITEM_TYPE, "icon" => $ICON];
	    
	    foreach($AUX_PROPS as $prop => $val)
	    {
	        $buffer[$prop] = $val;
	    }
	    
	    $arr[] = $buffer;
	    
	    $this->drvMEMORY->Set_Value("MENU:{$MENU}", $arr);
	    
	    return count($arr);
	}
	
	public function Get_Menu_Items($MENU)
	{
	    return $this->drvMEMORY->Get_Value("MENU:{$MENU}");
	}
		
	public function Dump($HALT=false)
	{
	    $this->drvMEMORY->Dump_Arr($HALT);
	}
}

?>
