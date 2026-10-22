<?php 
/* 
$DATA =  array(
	"id",
	"name",
	"action",
	"header", 
	"width", 
	"hidden" => array("name", "value")
	"rows" => array(
		array(
			"type",
			"id",
			"name",
			"label", 
			"style",
			"value",
			"options" => array("text:value")) 
))); */ 

?>


<!-- EDIT VIEW START -->
<div class="box" style="margin-left: 8px; width: 640px;">
	<div  class="box-header" style="">&nbsp;<?php echo $DATA["title"]?></div>
	<div class="" style="background-color: #FFFFFF;">
		<form name="<?php echo $DATA["name"]?>" id="frm_<?php echo $DATA["name"]?>" action="<?php echo $DATA["action"]?>" method="post" onsubmit="" style="margin-bottom: 0px;">
			<input type="hidden" id="hid_act" name="act" value="edit">
			<input type="hidden" id="hid_modified" name="modified" value="0">
		<?php foreach ($DATA["hidden"] as $hidden) { ?>
			<input type="hidden" id="hid_<?php echo $hidden["name"]?>" name="<?php echo $hidden["name"]?>" value="<?php echo $hidden["value"]?>">
		<?php } ?>
			
			<table id="tbl_Items" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td id="edit_form" valign="top" style="">
<?php foreach ($DATA["rows"] as $row){ ?>
	<?php $cellWidth = intval(100 / count($row)) - 2;?>
						<div class="edit_data" style="<?php if($row[0]["type"] == "area") echo "height: 100px;"?>">
							<div class="edit_list" style="">
	<?php foreach ($row as $cell){ ?>
		<?php $formattedValue = (!empty($cell["formattedValue"]))?$cell["formattedValue"]:$cell["value"];?>
								<div id='lbl_<?php echo $cell["name"]?>' class="edit_list_item" style="height: 25px; width: <?php echo $cellWidth?>%; vertical-align: top;">
					 				<?php echo $cell["label"]?>
					 				<div id='dsp_<?php echo $cell["name"]?>' style="width: 99%; height: 20px; cursor: pointer;" onclick="showEditBox('<?php echo $cell["name"]?>');"><strong  style=""  onmouseover="this.style.color = '#2C6A8C';" onmouseout="this.style.color = '#434343';"><?php echo $formattedValue?></strong></div>

					 				<div id='edt_<?php echo $cell["name"]?>' class="input-group" style="display: none;">
		<?php
			switch($cell["type"])
			{ 
				case "raw":
						echo $cell["value"];
					  	break; 				
				case "text":	
					 					?><input id="inp_<?php echo $cell["name"]?>" type="text" name="<?php echo $cell["name"]?>" value="<?php echo $cell["value"]?>" class="form-control" <?php if(!empty($cell["style"])) echo "style=\"".$cell["style"]."\""?> disabled><?php
					 	break; 
				case "date":
										?><input id="inp_<?php echo $cell["name"]?>" class="datepicker" type="text" name="<?php echo $cell["name"]?>" value="<?php echo $cell["value"]?>" datepicker="true" <?php if(!empty($cell["style"])) echo "style=\"".$cell["style"]."\""?> disabled><?php 
						break;
				case "select":
										?><select id="inp_<?php echo $cell["name"]?>" name="<?php echo $cell["name"]?>" <?php if(!empty($cell["style"])) echo "style=\"".$cell["style"]."\""?> disabled><?php 
										foreach ($cell["options"] as $option_value => $option_text)
										{ 
					 						?><option <?php if(!empty($option_value)) echo "value=\"{$option_value}\"";?> <?php if($option_text == $cell["value"]) echo "SELECTED";?>><?php 
					 							echo $option_text;
					 						?></option><?php 
										}	 				
					 					?></select><?php 
					 	break;
				case "area":
										?><textarea id="inp_<?php echo $cell["name"]?>" name="<?php echo $cell["name"]?>" style="width: 95%;" disabled><?php echo $cell["value"]?></textarea><?php 
						break;
				case "checkbox":
										$style = (!empty($cell["style"]))?"style=\"".$cell["style"]."\"":"";
										$checked = ($cell["checked"])?"CHECKED":"";
										?><input id="hid_<?php echo $cell["name"]?>" type="hidden" name="<?php echo $cell["name"]?>" value=""><?php 
										?><input id="inp_<?php echo $cell["name"]?>" type="checkbox" value="<?php echo $cell["value"]?>" <?php $style?> onclick="updateChecked(this, 'hid_<?php echo $cell["name"]?>')" <?php echo $checked?> disabled><?php
						break;
			}?>
										<span class="input-group-btn">
											<button class="btn btn-primary btn-xs" style="" onclick="hideEditBox('<?php echo $cell["name"]?>'); return false;">X</button>
										</span>
					 				</div>	
					 			</div>
	<?php } ?>
							</div>
						</div>
<?php } ?>
					</td>
				</tr>
			</table>
		</form>
 	</div>			
	<div class="box-footer" style="">		
	</div>		
</div>

<script>
	function updateChecked(CHKBOX, HID)
	{
		var Obj = document.getElementById(HID);

		if(CHKBOX.checked)
		{
			Obj.value = "1";
		}else{
			Obj.value = "0";
		}
	}
			
	function showEditBox(NAME)
	{
		$("#dsp_" + NAME).hide();
		$("#edt_" + NAME).show();
		$("#inp_" + NAME).removeAttr("disabled"); 
		
		var buffer = parseInt($("#hid_modified").val()) + 1;
		$("#hid_modified").val(buffer);
	}
	
	function hideEditBox(NAME)
	{
		$("#edt_" + NAME).hide();
		$("#dsp_" + NAME).show();
		$("#inp_" + NAME).attr("disabled", "disabled"); 
		
		var buffer = parseInt($("#hid_modified").val()) - 1;
		$("#hid_modified").val(buffer);
	}
	
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

		$(function() {
		    $( ".datepicker" ).datepicker();
		});	

</script>
<!-- EDIT VIEW END -->
