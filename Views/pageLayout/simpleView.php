<?php

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
        <title><?php echo $objRegistry->Get_Value("SITE:NAME")?></title>
        <?php $modScriptImport->display(["output" => "css"]); ?>
        <?php $modScriptImport->display(["output" => "js", "location" => "header"]); ?>
    </head>
    <body class="text-center" style="background-color: #F9F9F9;">
<?php 
if($bit){ require("Modules/Utils/m_StartDebug.php");}    
?>       
        <div id="bg">
        	<img src="<?php echo $objRegistry->Get_Value("LOGIN:BACKGROUND")?>" alt="">
        </div>
        <header class="" style="">
		</header>
        <div class="container-fluid p-0">
          	<div class="row m-0"> 
              	<main class="p-0">      
            <?php $mainModule->display(); ?>
            	</main>
    		</div>
    	</div>  
<?php 
    if($bit){
        if($BENCHMARK) require("Modules/Utils/m_Benchmark.php"); 
        require("Modules/Utils/m_EndDebug.php");
    }
?>
		<?php $modScriptImport->display(["output" => "js", "location" => "footer"]); ?>
	</body>
</html>