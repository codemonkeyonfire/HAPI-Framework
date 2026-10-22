<?php
/**
 * Memory data storage Driver
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

class MemArrDriver
{
	private $arrBUFFER;
	
	
	public function __construct()
	{
		//  set static settings here 
		$this->arrBUFFER = array();
				
	}	
	
	public function get_value($KEY = "SITE:NAME")
	{
			list($CATEGORY, $SETTING) = explode(":", $KEY);
			
			return $this->arrBUFFER[$CATEGORY][$SETTING];
	}
	
    /**
     * Sets a config value in the 2d array
     * @param string $PATH reference of values by category:setting pair
     * @param string $VALUE  
     */			
	public function set_value($KEY, $VALUE)
	{
			//   reference of values by category:setting pair
			//	creates temp config setting if does not exist
			
			list($CATEGORY, $SETTING) = explode(":", $KEY);
			
			$this->arrBUFFER[$CATEGORY][$SETTING] = $VALUE;
	}	
	
	public function Get_Arrau()
	{
		$EXPIRY = ($TIME == "")?time()+31536000:$TIME;
		
		setcookie($NAME, $VAL, $EXPIRY, $DOMAIN);
	}

	public function Dump_Arr($HALT=false)
	{
	    echo "<pre>";
	    print_r($this->arrBUFFER);
	    echo "</pre>";
	    if($halt) die();
	}
}

?>