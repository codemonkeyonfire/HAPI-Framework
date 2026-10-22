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
namespace Handlers\Util;

class DebugHandler
{
	private $objDB = "";
	private $arrOUTPUT;
	private $count;
	private $time_start;
	
	public function __construct()
	{
		$this->count = 0;
		$this->time_start = microtime(true);
		$this->arrOUTPUT[] = "debug started";
	}
	
	static function Display_Array($ARRAY, $DIE="false")
	{
		echo "<div style='width: 600px; border: 4px solid #AC0000; background-color: #FFECEC; text-align: left; position: absolute; z-index: 10; top: 10px;, left: 10px;'><pre>";
		print_r($ARRAY);
		
		if($DIE)
			die("</pre><div>");
		else 
			echo "</pre><div>";
	}
	
	function Output($LINE)
	{
		$this->count++;
		$this->arrOUTPUT[] = $this->count." : ".substr($LINE, 0, 40);	
	}
	
	function Display_Console()
	{
		$output = implode("<br>", $this->arrOUTPUT);
		$console = "<div class=\"console_container\" onclick=\"document.getElementById('debug_console').style.display='block';\">console</div>";
		$console .= "<div id=debug_console class=\"console_screen\" onclick=\"this.style.display='none';\">";
		$console .= $output;	
		$console .= "</div>";		
		
		return $console;
	}
	
	function Display_Error($ERROR)
	{
		
		$console = "<div style=\"position: absolute; width: 800px; height: 12px; top: 100%; left: 50%; margin-left: -400px; margin-top: -12px; background-color: #6B7A84; font-size: 10px; border-width: 0px 4px 4px 4px; border-style: solid; border-color: #6B7A84; color: #C61F1F; text-align: left; padding-left: 4px;\" onclick=\"document.getElementById('debug_error').style.display='block';\">ERROR</div>";
		$console .= "<div id=debug_error style=\"position: absolute; width: 800px; height: 200px; top: 100%; left: 50%; margin-left: -400px; margin-top: -214px; border-width: 4px 4px 0px 4px; border-style: solid; border-color: #6B7A84; background-color: #000000; color: #ffffff; text-align:left; display: none; padding-left: 4px;\" onclick=\"this.style.display='none';\">";
		$console .= $ERROR;	
		$console .= "</div>";		
		
		return $console;
	}	
	
	function Display_Popup($MSG)
	{
		
	}
}
?>