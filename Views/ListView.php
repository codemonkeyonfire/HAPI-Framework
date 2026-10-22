<?php 
/* 
$DATA[] =>  
	header, 
	width, 
	selectable,
	form => 
		name,
		url,
		hidden[fld] => 
			name
	rows[] => 
		height,
		onclick, 
		cells[] => 
			data,
			width, 
			bg_color, 
			txt_color
	controls[] => 
		text,
		action 
*/ 

$formWidth = (!empty($DATA["form"]["width"]))?$DATA["form"]["width"]:"99%";
?>

<!-- LIST VIEW START -->
<div class="box" style="margin-left: 8px; width: <?php echo $formWidth?>">
	<div  class="box-header" style="">&nbsp;<?php echo $DATA["header"]?></div>
	<div class="" style="width: 100%; text-align: center;">
	<form name="<?php echo $DATA["form"]["name"]?>" method="post" action="<?php echo $DATA["form"]["url"]?>">
<?php foreach ($DATA["form"]["hidden"] as $fld => $val) { ?>
		<input type="hidden" name="<?php echo $fld?>" value="<?php echo $val?>">
<?php } ?>
		<table cellpadding="0" cellspacing="0" class="row-table" style="margin-left: 0px;">
<?php foreach ($DATA["rows"] as $row) { ?>
 			<tr>
 		<?php foreach ($row["cells"] as $cell) { ?>
				<td class="row-cell" style="padding: 4px; <?php echo $cell["style"]?>" onclick="<?=$cell["onclick"]?>">
					<?php echo $cell["data"]?> 
				</td>
		<?php } ?>		 				 				 		
			</tr>
<?php } ?>
			<tr id="insert_row" style="display: none;">
 		<?php foreach ($DATA["insert_row"]["cells"] as $cell) { ?>
				<td class="row-cell" style="<?php echo $cell["style"]?>" onclick="">
					 <?php echo $cell["inputWidget"]?> 
				</td>
		<?php } ?>				
			</tr>
		</table>
	</form>
	</div>	
	<div class="box-footer" style="text-align: right;">
<?php if(!empty($DATA["controls"])){?>
	<?php foreach($DATA["controls"] as $control){?>
		<div class="btn btn-primary" style="" onclick="<?php echo $control["action"]?>"><?php echo $control["text"]?></div>
	<?php } ?>
<?php } ?>
	</div>
</div>
<!-- LIST VIEW END -->
