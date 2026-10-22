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

class MessageBoxHandler
{
	public function __construct()
	{
		
	}
	
	static function Alert_Centered($WINDOW_NAME="acMsg", $MESSAGE="", $TIMEOUT=0, $CLOSE_ACTION="hideAlertCentered()")
	{
		$display = "block";
		
		$output = "<script>";
			
		if($TIMEOUT > 0) 
		{
			$mSecs = $TIMEOUT * 1000;
			$output .= "var t=setTimeout(\"$CLOSE_ACTION\", $mSecs);";
		}
		
		if($CLOSE_ACTION == "hideAlertCentered()")
		{
			$output .= "function showAlertCentered(MSG)
						{
							document.getElementById('{$WINDOW_NAME}Screen').style.display='block';
							document.getElementById('{$WINDOW_NAME}Box').style.display='block';
							
							if(MSG != \"\")
								document.getElementById('{$WINDOW_NAME}Text').innerHTML = MSG;							
						}
						
						function hideAlertCentered()
						{
							document.getElementById('{$WINDOW_NAME}Screen').style.display='none';
							document.getElementById('{$WINDOW_NAME}Box').style.display='none';
						}";
			$display = ($TIMEOUT<0)?"block":"none";
		}
			
	 	$output .= "</script>
	 		<div id=\"{$WINDOW_NAME}Screen\" class=\"transparent\" style=\"display: $display;\" onclick=\"$CLOSE_ACTION;\"></div>
			<div id=\"{$WINDOW_NAME}Box\" class=\"messageBox\" style=\"width: 200px; height: 90px; margin-left: -100px; margin-top: -30px; display: $display;\">
				<div id=\"{$WINDOW_NAME}Text\" style=\"font-size: 20px;\">
					$MESSAGE 
				</div> 
				<div style=\"position: absolute; width: 40px; height: 20px; top: 85%; left: 80%; margin: -5px 0px 0px -4px;\">
					[<strong style=\"cursor: pointer;\" onclick=\"$CLOSE_ACTION\">CLOSE</strong>]
				</div>
			</div>";
		
		return $output;
	}
}
?>