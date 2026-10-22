<?php 
/* 
 *   
 * 
$DATA[ 
	title, 
	options[](striped|dark|bordered|dark|hover|sm),
	headers[],
	headerStyles[],	
	rows[][ 
		state,
		onclick, 
		cells[][ 
			state(active|primary|secondary|success|danger|warning|info|light|dark),
			data,
			style
		]
	]
] 
*/ 
$tableClasses = "";
foreach($DATA["options"] as $option)
{
	$tableClasses .= " table-{$option}";
}

$h = 0;
?>

<!-- TABLE VIEW START -->
<?php if(!empty($DATA["title"])){?>
	<h2 class="content_pad"><?php echo $DATA["title"]?></h2>
<?php } ?>
	<table class="table <?php echo $tableClasses;?>" style="<?php echo $DATA["style"]?>">
		<thead>
			<tr>
<?php foreach ($DATA["headers"] as $header){?>
				<th scope="col" style="<?php echo $DATA["headerStyles"][$h]?>"><?php echo $header?></th>
<?php $h++;} ?>
			</tr>
		</thead>
		<tbody>
<?php foreach ($DATA["rows"] as $row) { ?>
 			<tr class="<?php echo ($row["state"])?"table-".$row["state"]:"";?>" onclick="<?php echo $row["onclick"]?>">
 	<?php foreach ($row["cells"] as $cell) { ?>
				<td id="td-<?php echo $cell["id"]?>" class="<?php echo ($cell["state"])?"table-".$cell["state"]:"";?>" style="<?php echo $cell["style"]?>" onclick="<?=$cell["onclick"]?>">
					<?php echo $cell["data"]?> 
				</td>
	<?php } ?>		 				 				 		
			</tr>
<?php } ?>
		</tbody>
	</table>

<!-- TABLE VIEW END -->
