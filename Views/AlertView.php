<?php 
/* 
$DATA =  array(
	"id",
	"header", 
	"body", 
	"dismissable",
	"controls" => array(
		array{
			"text",
			"onClick") 
))); */ 
if(empty($DATA["state"]))
{
    $DATA["state"] = "primary";
}

$classes = ($DATA["dismissable"] == true)?" alert-dismissible fade show":"";
?>

<!-- MESSAGEBOX VIEW START -->
<div class="alert alert-<?php echo $DATA["state"];?><?php echo $classes;?>" style="" role="alert">
<?php if(!empty($DATA["header"])){?>
	<h4 class="alert-heading"><?php echo $DATA["header"]?></h4>
<?php } ?>
	<?php echo $DATA["body"];?>
<?php if(!empty($DATA["controls"])){?>	
	<div class="btn-group" role="group">
	<?php foreach ($DATA["controls"] as $button) { ?>
		<button class="btn btn-lg btn-primary" style="" onclick="<?php echo $button["onClick"]?>"><?php echo $button["text"]?></button>
	<?php } ?>	
	</div>
<?php } ?>	
<?php if($DATA["dismissable"]){?>	
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    	<span aria-hidden="true">&times;</span>
    </button>
<?php } ?>
</div>

<!-- MESSAGEBOX VIEW END -->