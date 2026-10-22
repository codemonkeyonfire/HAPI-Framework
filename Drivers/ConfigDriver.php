<?php


/**
* Web App Configuration Driver 
* 
* formats config varibles and reads values from that format
* @author Carlos Ferrera
* @version 1.0
* @package configuration
*/
/**
* The first example class, this is in the same package as the
* procedural stuff in the start of the file
* @package configuration
* @subpackage classes
*/
namespace Drivers;

class ConfigDriver
{
   /**
    * no more then 3 dimensions i.e. array(array(array(value, ...)))
    * @access private
    * @see __construct()
    * @var array 
    */	
	private $arrSETTINGS; 
	
   /**
    * used to indicate that config value is stored in database
    * @access private
    * @see Load_From_DB()
    * @var array 
    */		
	private $arrIN_DATABASE;
	
	public $DB_TABLE;

    /**
     * Constructor sets up {@link $arrSETTINGS} {@link $arrIN_DATABASE}
     */	
	public function __construct()
	{
		//  set static settings here 
		
		$this->arrSETTINGS = array(array());
		$this->arrIN_DATABASE = array();
		
		$this->numINDEX = 0;
		$this->numTOTAL = 0;	
		
		$this->DB_TABLE = "Settings";	
		
	}	
	
    /**
     * grabs config value from a 2d array
     * @param string $PATH reference of values by category:setting pair
     * @return integer|string
     */		
	public function Get_Value($PATH = "SITE:NAME")
	{
			list($CATEGORY, $SETTING) = explode(":", $PATH);
			
			return $this->arrSETTINGS[$CATEGORY][$SETTING];
	}
	
    /**
     * Sets a config value in the 2d array
     * @param string $PATH reference of values by category:setting pair
     * @param string $VALUE  
     */			
	public function Set_Value($PATH, $VALUE)
	{
			//   reference of values by category:setting pair
			//	creates temp config setting if does not exist
			
			list($CATEGORY, $SETTING) = explode(":", $PATH);
			
			$this->arrSETTINGS[$CATEGORY][$SETTING] = $VALUE;
	}	
	
	

    /**
     * loads config values from a database
     * @uses Set_Value
     * @param 'DataBaseDriver' $objDB_CONN 
     */			
	public function Load_From_DB($objDB_CONN)
	{
		$strSQL = "SELECT * FROM $this->DB_TABLE";
		$objDB_CONN->Query($strSQL);
		
		$arrDYNASETTINGS = $objDB_CONN->Fetch_Arrays();
		
		foreach($arrDYNASETTINGS as $arrSETTING)
		{
			$this->arrIN_DATABASE[$arrSETTING["Path"]] = 1;
			$this->Set_Value($arrSETTING["Path"], $arrSETTING["Value"]);
		}
		
		//echo "<pre>"; print_r($this->arrSETTINGS); die();
	}		
	
    /**
     * Unloads config values to a database
     * @uses Get_Value
     * @param 'DataBaseDriver' $objDB_CONN 
     */			
	public function Unload_To_DB($objDB_CONN)
	{
	
		foreach ($this->arrIN_DATABASE as $ITEM)
		{
			$VALUE = $this->Get_Value($ITEM);
			
			$strSQL = "UPDATE $this->DB_TABLE SET Value = '$VALUE' WHERE Path = '$ITEM'";		
			$objDB_CONN->Query($strSQL);	
		}
	}
}

?>