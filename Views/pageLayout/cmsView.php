<?php

$logoModule = new \Modules\Layout\LogoModule($objRegistry, $objDatabase);
$logoModule->run();

$headerModule = new \Modules\Layout\HeaderModule($objRegistry, $objDatabase);
$headerModule->run();

$mainMenuModule = new \Modules\Navigation\NavMenuModule($objRegistry, $objDatabase);
$mainMenuModule->run(["menu" => "public"]);

$logonMenuModule = new \Modules\Navigation\NavMenuModule($objRegistry, $objDatabase);
$tmp = ($objRegistry->Get_Value("USER:USERNAME")=="guest")?"Dashboard":$objRegistry->Get_Value("USER:USERNAME");
$logonMenuModule->run(["menu" => "logon", "displayText" => $tmp]);

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
        <header style="">
    		<div class="container-fluid" style="" >
    <?php $headerModule->display();?>
    		</div>
    		<nav id="cms-menu-sticky" class="navbar navbar-expand-lg navbar-dark flex-md-nowrap p-0 shadow">
    			<div class="container-fluid g-0" style="background-color: #00AEF0;">
                   	<div class="navbar-brand col-md-3 col-lg-2 me-0 px-3" style="text-align: left;"><?php $logoModule->display()?></div>
                   	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                       	<span class="navbar-toggler-icon"></span>
                   	</button>  
             		<div class="collapse navbar-collapse cms-menu" id="navbarMenu">
            			<?php $mainMenuModule->display(["orient"=>"horizontal"]); ?>
            			<?php $logonMenuModule->display(["orient"=>"dropdown"]); ?>
                	</div>     
                </div>            	
        	</nav>
    	</header>
    	<main class="p-0 cms-content" style=""> 
            <div class="container-fluid p-0">
    <?php $mainModule->display(); ?>
        	</div>  
        </main>
<?php 
    if($bit){
        if($BENCHMARK) require("Modules/Utils/m_Benchmark.php"); 
        require("Modules/Utils/m_EndDebug.php");
    }
?>
        <footer class="footer" style="">
          <div class="container" style="padding: 0 23px;">
		<?php $footerModule->display(); ?>
		<?php $modScriptImport->display(["output" => "js", "location" => "footer"]); ?>
		  </div>
		</footer>
	</body>
</html>