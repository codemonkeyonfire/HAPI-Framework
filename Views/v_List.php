<?php 
/* 
$DATA[  
	header, 
	width, 
	selectable,
	table_options[](striped|dark|bordered|dark|hover|sm)
	form[ 
		name,
		url,
		hidden[
			fld => name
		] 
	]
	rows[][
		height,
		onclick, 
		cells[][
			type(active|primary|secondary|success|danger|warning|info|light|dark)
			data,
			width, 
			bg_color, 
			txt_color
		]
	]
	insert_row[
		cells[][
			type(active|primary|secondary|success|danger|warning|info|light|dark),
			style,
			inputWidget
		]
	]
	controls[][ 
		text,
		action 
	]
]
*/ 

$formWidth = (!empty($DATA["form"]["width"]))?$DATA["form"]["width"]:"99%";

foreach($DATA["table_options"] as $option)
	$tableClasses .= " table-{$option}";

		
?>
<!-- LIST VIEW START -->
<div class="row no-gutters">
	<div class="col-12" style="">
		<h2><?php echo $DATA["header"]?></h2>
	</div>
</div>
<form id="frm_<?php echo $DATA["form"]["name"]?>" name="<?php echo $DATA["form"]["name"]?>" method="post" action="<?php echo $DATA["form"]["url"]?>">
<div class="row no-gutters" style="">
	<div class="col-12">
<?php foreach ($DATA["form"]["hidden"] as $fld => $val) { ?>
			<input type="hidden" name="<?php echo $fld?>" value="<?php echo $val?>">
<?php } ?>
			<table cellpadding="0" cellspacing="0" class="table<?php echo $tableClasses;?>" style="margin-left: 0px;">
<?php foreach ($DATA["rows"] as $row) { ?>
				<tr>
 		<?php foreach ($row["cells"] as $cell) { ?>
        			<td class="<?php echo (!empty($cell["type"]))?"table-".$cell["type"]:""?>" style="<?php echo $cell["style"]?>" onclick="<?=$cell["onclick"]?>" <?php echo (!empty($cell["span"]))?"colspan='".$cell["span"]."'":""?>>
        				<?php echo $cell["data"]?> 
        			</td>
		<?php } ?>		 				 				 		
				</tr>
<?php } ?>
				<tr id="insert_row" style="display: none;">
 		<?php foreach ($DATA["insert_row"]["cells"] as $cell) { ?>
        			<td class="<?php echo (!empty($cell["type"]))?"table-".$cell["type"]:""?>" style="<?php echo $cell["style"]?>" onclick="">
        				 <?php echo $cell["inputWidget"]?> 
        			</td>
		<?php } ?>				
    			</tr>
    		</table>
	</div>
</div>	
<?php  $align = $DATA["alignControls"]??"right";?>
<div class="row" style="text-align: <?php echo $align?>>;">
	<div class="col-12">
<?php if(!empty($DATA["controls"])){?>
	<?php foreach($DATA["controls"] as $control){?>
		<div class="btn btn-secondary" style="" onclick="<?php echo $control["action"]?>"><?php echo $control["text"]?></div>
	<?php } ?>
<?php } ?>
<?php 
if(!empty($DATA["form"]["url"]) )
{
    if($DATA["form"]["submitText"] != "--hidden--")
    {
?>									
		<input id="sub_<?php echo $DATA["form"]["name"]?>" type="submit" class="btn btn-primary" style="font-size: 16px;" value="<?php echo $DATA["form"]["submitText"]?>">	
<?php 
    }
} 
?>	
	</div>
</div>
</form>
<!-- LIST VIEW END -->
