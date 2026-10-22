<?php 
/* 
$DATA[
	message,
]

}
*/

//DebugHandler::Display_Array($DATA);
?>

<!-- LOGIN VIEW START -->
<?php if(!empty($DATA["logo"])){?>
      			<img class="mb-4" src="<?php echo $DATA["logo"]?>" width="400px;" alt="">
<?php }else{ ?>
				<div class="hero text-info"><?php echo $DATA["sitename"]?></div>
<?php } ?> 
			<div style="background-color: rgba(255,255,255, 0.3); max-width: 400px; margin: 0 auto; padding: 20px; border-radius: 10px;">
			<form class="form-signin" style="" method="POST" action="<?php echo $DATA["url"]?>" name="frm_login">
				<input type="hidden" name="act" value="login">
		    	<h2 class="form-signin-heading" style="color: #ffffff;"><?php echo $DATA["message"]?></h2>
		        <label for="inputEmail" class="sr-only">Username</label>
		        <input type="text" id="inputEmail" name="username" class="form-control mb-2" style="" placeholder="Username" required autofocus>
		        <label for="inputPassword" class="sr-only">Password</label>
		        <input type="password" id="inputPassword" name="password" class="form-control mb-2" style="" placeholder="Password" required>
		        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		        
			</form>
			</div>
			<p class="mt-5 mb-3">&copy; 2021</p>
<!-- LOGIN VIEW END -->