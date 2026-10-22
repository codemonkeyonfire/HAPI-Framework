
<?php
$i = 0;
?>
<!-- GRID VIEW START -->
	<h3 style="padding-left: 4px;"><?=$DATA["header"]?></h3>
	<table cellpadding="0" cellspacing="4">
	<?php foreach ($DATA["items"] as $item) { ?>
		<?php if($i % 4 == 0){?> <tr> <?php } ?>
		<td style="width: 100px; background-color: #ECECEC; border: 1px solid #696969; height: 120px;" valign="top">
			<div style="width: 100%; background-color: #<?=$item["dividerColor"]?>; border-bottom: 1px solid #696969;">
				<img style="border-right: 1px solid #000000; height: 60px;" src="<?=$item["image"]?>">
			</div>
			<div>
				<?=$item["summary"]?> 
			</div>
		</td>
		<?php if($i % 4 == 3){?> </tr> <?php } $i++;?>		
	<?php } ?>
	</table>
<!-- GRID VIEW END -->