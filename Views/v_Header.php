<?php 
/* 
$DATA[
	message,
]

}
*/

//DebugHandler::Display_Array($DATA);
?>

<!-- VIEW HEADER START -->
			<div class="row ui-clearfix " style="background-color: #ffffff;">
				<div class="col" style="">
					<div class="justify-content-center" style=" padding: 15px 0">
						<img style="width: 200px; " src="images/core_logo2.png">
					</div>
				</div>	
<?php foreach($DATA["features"] as $item){?>
				<div class="col-2 align-self-center" style="">
					<div class="" style="">
						<a class="awesomelink" href="<?php echo $item["url"]?>" style="">
							<i class="fas fa-<?php echo $item["icon"]?>" style="margin-right:5px;"></i>
							<div class="awesomelink-text">
								<?php echo $item["text"]?>
							</div>
						</a>
					</div>
				</div>	
<?php } ?>
			</div>
<!-- VIEW HEADER END -->