<?php
$logoModule = new \Modules\Layout\LogoModule($objRegistry, $objDatabase);
$logoModule->run();

$mainMenuModule = new \Modules\Navigation\NavMenuModule($objRegistry, $objDatabase);
$mainMenuModule->run(["menu" => "guest"]);

$footerModule = new \Modules\Layout\FooterModule($objRegistry, $objDatabase);
$footerModule->run();


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
    <body class="" style="background-color: #F9F9F9;">
<?php 
if($bit){ require("Modules/Utils/m_StartDebug.php");}    
?>    
        <div id="bg">
        	<img src="<?php echo $objRegistry->Get_Value("LOGIN:BACKGROUND")?>" alt="">
        </div>
        <header class="navbar navbar-expand-lg navbar-dark sticky-top flex-md-nowrap p-0 shadow" style="background-color: #00AEF0;">
        	<div class="container-fluid">
            	<div class="navbar-brand col-md-3 col-lg-2 me-0 px-3" style="text-align: left;"><?php $logoModule->display()?></div>
              	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                	<span class="navbar-toggler-icon"></span>
              	</button>  
              	<?php $mainMenuModule->display(["orient"=>"dropdown"]); ?>
			</div>
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
        <footer class="fixed-bottom py-2 bg-light" style="opacity: 0.5">
          <div class="container">
		<?php $footerModule->display(); ?>
		<?php $modScriptImport->display(["output" => "js", "location" => "footer"]); ?>
		  </div>
		</footer>
	</body>
</html>