<?php 
//  OLD VERSION -  DONOT USE


/* 
$DATA =  array(
	"name",
	"target", 
	"method", 
	"fields" => array( 
		"visible" =>
			array(
				"type",
				"id",
				"name", 
				"value",
				"options" => array(key => val),
				"actions"  => array(event => action)
		"hidden" =>
			array(
				"id",
				"value"
	"controls" => array(
		array{
			"text",
			"action") 
))); */ 

//DebugHandler::Display_Array($DATA);

$DatePickerIncluded = false;
?>


<!-- FORM VIEW START -->
<div class="" style="width: 100%; margin-top: 0px; background-color: #008D4C;">
	<div  class="" style="border-bottom: 2px solid #00773F; color: #FFF;">&nbsp;<?php echo $DATA["header"]?></div>
	<div class="" style="background-color: #FFFFFF;">
		<form name="<?php echo $DATA["name"]?>" id="frm_<?php echo $DATA["name"]?>" action="<?php echo $DATA["target"]?>" method="<?php echo $DATA["method"]?>" style="margin: 0px;">
<?php foreach ($DATA["fields"]["hidden"] as $fld) {?>
			<input type="hidden" name="<?php echo $fld["id"]?>" value="<?php echo $fld["value"]?>">
<?php } ?>		
	 	<table id="edit_form" style="width: 99%; border-collapse: collapse; border-spacing: 0;">
<?php 
	foreach ($DATA["fields"]["visible"] as $fld) 
	{ 
		$inputStr = "";
	 	echo "<tr id=\"row_{$fld["id"]}\">";
	 		
	 	switch($fld["type"])
	 	{
	 		case "header":
	 			echo "<td id=\"lbl_{$fld["id"]}\" colspan=\"2\"><div style=\"height: 25px; font-size: 18px; text-align: center; border-bottom: 2px solid #ECECEC; width: 100%;\">{$fld["value"]}</div></td>"; 		
				continue(2);
				break;
			case "space":
				echo "<td id=\"spc_{$fld["id"]}\" colspan=\"2\" style=\"\">&nbsp;</td>";
				continue(2);
				break;
			case "raw":
				$inputStr =  $fld["value"];
				break;
			case "textarea":
				echo "<td align=\"center\" class=\"data_input\" style=\"height: 90px; padding-top: 0px;\" colspan=\"2\">";
				echo "<textarea class=\"data_edit\" style=\"width: 96%; color: #696969; padding: 2px;\"  id=\"txa_{$fld["id"]}\" name=\"{$fld["id"]}\" title=\"{$fld["label"]}\">{$fld["label"]}</textarea>";
				echo "</td>";
				continue(2);
				break;	
			case "text":
					$inputStr =  "<div style=\"{$fld["style"]}\">{$fld["value"]}</div>";
					break;							
			case "textbox":
				$inputStr =  "<input class=\"data_edit\" type=\"text\" id=\"txt_{$fld["id"]}\" name=\"{$fld["id"]}\" style=\"{$fld["style"]}\" value=\"{$fld["value"]}\">";
				break;
			case "date":
				$inputStr =  "<input class=\"data_edit datepicker\" type=\"text\" id=\"date_{$fld["id"]}\" name=\"{$fld["id"]}\" value=\"{$fld["value"]}\">";
				break;		
			case "time":
				$inputStr =  "<input class=\"data_edit timepicker\" type=\"text\" id=\"time_{$fld["id"]}\" name=\"{$fld["id"]}\" style=\"{$fld["style"]}\" value=\"{$fld["value"]}\">";
				break;						
			case "checkbox":
				$checked = (!empty($fld["selected"]))?"CHECKED":"";
				$inputStr =  "<input class=\"data_edit\" type=\"checkbox\" id=\"chk_{$fld["id"]}\" name=\"{$fld["id"]}\" style=\"{$fld["style"]}\" value=\"{$fld["value"]}\" {$checked}>";
				break;	
			case "checkboxes":
				$inputStr = "<table><tr>";
				if(is_array($fld["options"]))
				{
					foreach ($fld["options"] as $val => $txt)
					{
						$checked = ($val == $fld["selected"])?"CHECKED":"";
						$inputStr .=  "<td style=\"width: 10px;\"><input class=\"data_edit\" style=\"display: inline-block;\" type=\"checkbox\" id=\"chk_{$fld["id"]}_{$val}\" name=\"{$fld["id"]}_{$val}\" style=\"{$fld["style"]}\" value=\"{$val}\" {$checked}></td><td style=\"width: 100px;\">{$txt}</td></div>";
					}
				}else{
					$opt = explode("," , $fld["options"]);
					foreach ($opt as $val)
					{
						$checked = ($val == $fld["selected"])?"CHECKED":"";
						$inputStr .=  "<td style=\"width: 10px;\"><input class=\"data_edit\" style=\"display: inline-block;\" type=\"checkbox\" id=\"chk_{$fld["id"]}_{$val}\" name=\"{$fld["id"]}_{$val}\" style=\"{$fld["style"]}\" value=\"{$val}\" {$checked}></td><td style=\"width: 100px;\">{$val}</td></div>";
					}
				}	
				$inputStr .= "</tr></table>";
				break;				
			case "password":
				echo "<input class=\"data_edit\" style=\"width: 200px;\" id=\"pwd_{$fld["id"]}\" type=\"password\" name=\"{$fld["id"]}\" value=\"{$fld["value"]}\" style=\"{$fld["style"]}\">";
				break;	
			case "radiopanel":
				$inputStr = "<table><tr>";
				if(is_array($fld["options"]))
				{
					foreach ($fld["options"] as $val => $txt)
					{
						$checked = ($val == $fld["selected"])?"CHECKED":"";
						$inputStr .=  "<td style=\"width: 10px;\"><input class=\"data_edit\" style=\"display: inline-block;\" type=\"radio\" id=\"chk_{$fld["id"]}_{$val}\" name=\"{$fld["id"]}\" style=\"{$fld["style"]}\" value=\"{$val}\" {$checked}></td><td style=\"width: 100px;\">{$txt}</td></div>";
					}
				}else{
					$opt = explode("," , $fld["options"]);
					foreach ($opt as $val)
					{
						$checked = ($val == $fld["selected"])?"CHECKED":"";
						$inputStr .=  "<td style=\"width: 10px;\"><input class=\"data_edit\" style=\"display: inline-block;\" type=\"radio\" id=\"chk_{$fld["id"]}_{$val}\" name=\"{$fld["id"]}\" style=\"{$fld["style"]}\" value=\"{$val}\" {$checked}></td><td style=\"width: 100px;\">{$val}</td></div>";
					}
				}
				$inputStr .= "</tr></table>";
				break;								
			case "select":
				$inputStr =  "<select class=\"data_edit\" id=\"sel_{$fld["id"]}\" name=\"{$fld["id"]}\" style=\"{$fld["style"]}\" {$fld["events"]}>";
				$inputStr .= "<option value=0>-- NONE SELECTED --</option>";
				
				if(is_array($fld["options"])) 
				{
					foreach ($fld["options"] as $val => $txt) 
					{
						$selected = ($val ==  $fld["value"])?"SELECTED":"";
						$inputStr .= "<option value=\"{$val}\" {$selected}>{$txt}</option>";
					}
				}else{
					$opt = explode("," , $fld["options"]);
					foreach ($opt as $val) 
					{
						$selected = ($val ==  $fld["value"])?"SELECTED":"";
						$inputStr .= "<option {$selected}>{$val}</option>";
					}
				}
				
				$inputStr .= "</select>";
				break;
	 	}			
?>
	 			<td align="left" class="data_label" style="height: 40px; width: 130px; margin: 0px;" id="lbl_<?php echo $fld["id"]?>">
	 				<?php echo $fld["label"]?>
	 			</td>
	 			<td align="center" class="data_input">
	 				<?php echo $inputStr?>
	 			</td>
			</tr>			
<?php } ?>
		</table>
		</form>
	</div>
	
	<div class="" style="">		
	 	<table style="margin-left: auto; margin-right: auto;" border="0" cellpadding="0" cellspacing="0">
			<tr>
<?php		
		foreach ($DATA["controls"] as $control)
		{	
			if(!empty($output))	$output .= "<td style=\"width: 10px; color: #ffffff;\"></td>";
			$output .= "<td class=\"section-button\" id=\"but_".$control["name"]."\" style=\"width: 120px; color: #ffffff;\" onclick=\"".$control["action"]."\">".$control["text"]."</td>";
		}	

		echo $output;		
?>												
			</tr>
		</table>
	</div>		
</div>

<script>
	$('.timepicker').wickedpicker();
	$('.datepicker').datepicker();
	
	$("textarea").focus(
			function(){
				if($(this).val() == $(this).attr("title"))
					$(this).val("");	
			}
		);
			
		$("textarea").blur(
			function(){
				if($(this).val() == "")
					$(this).val($(this).attr("title"));
			}
		);
		
	$('#but_submit').click(function() { 
		$('#frm_<?php echo $DATA["name"]?>').submit();
	});
<?php if($DatePickerIncluded) {?>

//$( ".datepicker" ).datepicker();

<?php } ?>
</script>
<!-- FORM VIEW END -->
