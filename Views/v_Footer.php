<?php 
/* 
$DATA[
	message,
]

}
*/

//DebugHandler::Display_Array($DATA);
?>

<!-- VIEW FOOTER START -->
<div class="row ui-clearfix g-0" style="background-color: #000;">
	<div class="col-4">
	</div>
	<div class="col text-center"><div class="" style="font-size: 10px; color: #FFF; margin-top: 22px;">Core Mental Health Services &copy;<?php echo date("Y");?></div></div>
	<div class="col-4">
<?php if($DATA["facebookURL"]){?> 
		<a class="btn btn-link btn-floating btn-lg text-light m-1" href="<?php echo $DATA["facebookURL"]?>" role="button" data-mdb-ripple-color="dark"><i class="fab fa-facebook-f"></i></a>
<?php } ?>
<?php if($DATA["twitterURL"]){?> 
		<a class="btn btn-link btn-floating btn-lg text-light m-1" href="<?php echo $DATA["twitterURL"]?>" role="button" data-mdb-ripple-color="dark" ><i class="fab fa-twitter"></i></a>
<?php } ?>
<?php if($DATA["instagramURL"]){?> 
		<a class="btn btn-link btn-floating btn-lg text-light m-1" href="<?php echo $DATA["instagramURL"]?>"  role="button" data-mdb-ripple-color="dark" ><i class="fab fa-instagram"></i></a>
<?php } ?>
<?php if($DATA["linkedinURL"]){?> 
		<a class="btn btn-link btn-floating btn-lg text-light m-1" href="<?php echo $DATA["linkedinURL"]?>" role="button" data-mdb-ripple-color="dark" ><i class="fab fa-linkedin"></i></a>
<?php } ?>	
	</div>
</div>
<!-- VIEW FOOTER END -->