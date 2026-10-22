<?php
/**
 *Cookie varibles Driver
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

class CookieDriver
{
	private $numINDEX;
	private $numTOTAL;
	private $arrCOOKIES;
	
	
	public function __construct()
	{
		//  set static settings here 

				
	}	
	
	public function get_value($PATH = "SITE:NAME")
	{
		//   reference of values by category:setting pair
		if(isset($_COOKIE[$PATH]))
		{
			return $_COOKIE[$PATH];
		}else{
			return 0;
		}
	}
	
	public function set_value($PATH, $VALUE)
	{
		setcookie($PATH, $VALUE);
		$this->numTOTAL++;
		$this->arrCOOKIES[$this->numINDEX] = $PATH;		
		$this->numINDEX++;
	}	
	
	public function Set_Cookie($NAME, $VAL, $TIME, $DOMAIN)
	{
		$EXPIRY = ($TIME == "")?time()+31536000:$TIME;
		
		setcookie($NAME, $VAL, $EXPIRY, $DOMAIN);
	}

}

?>