<?php
/**
 * Database Handler
 * can only implement drivers
 * can only use other handlers if they are passed by reference
 * Do not reference Globals except for lib paths (explicitly pass values to functions)
 * can implement base class if and only if base class used by more than one handlers
*
* @author Carlos Ferrera
* @version 3.0
* @package insideArtCenter
* @subpackage common
* @date 6/14/12
*/
namespace Handlers\General;

class BreadcrumbHandler 
{
	private $Settings;
	
	function __construct($SETTINGS="")
	{
		$this->Settings = $SETTINGS;
		
	}
	
	function Generate($BC_NAME="BreadCrumbs")
	{
		return "<div id=\"{$BC_NAME}\">Home</div>\n";
	}
	
	function SetTrail($BC_NAME="BreadCrumbs", $CRUMBS)
	{
		$output = "";
		$cnt = count($CRUMBS);
		$i = 0;
		foreach ($CRUMBS as $name => $link)
		{
			if($output != "")
				$output .= " > ";
				
			if($i < $cnt-1)	
				$output .= "<a href='$link'>{$name}</a>";
			else	
				$output .= "{$name}";
				
			$i++;
		}
		
		return "<script>document.getElementById(\"{$BC_NAME}\").innerHTML = \"{$output}\"</script>\n";		
	}
}
?>