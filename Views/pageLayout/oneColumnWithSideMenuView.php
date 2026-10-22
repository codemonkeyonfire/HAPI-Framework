<?php
require("Modules/Layout/m_Header.php");
require("Modules/Navigation/m_NavMenu.php");
require("Modules/Layout/m_Logo.php");

$logoModule = new LogoModule($objRegistry, $objDatabase);
$logoModule->run();

$mainMenuModule = new NavMenuModule($objRegistry, $objDatabase);
$mainMenuModule->run(["menu" => "main"]);

$settingsMenuModule = new NavMenuModule($objRegistry, $objDatabase);
$settingsMenuModule->run(["menu" => "settings", "displayText" => $objRegistry->Get_Value("USER:USERNAME")]);

$sideMenuModule = new NavMenuModule($objRegistry, $objDatabase);
$sideMenuModule->run(["menu" => "sidemenu"]);

?>

<!DOCTYPE html>
<html>
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
    <body style="">
<?php 
if($bit){ require("Modules/Utils/m_StartDebug.php");}    
?>      
        <header class="navbar navbar-expand-lg navbar-dark sticky-top flex-md-nowrap p-0 shadow" style="background-color: #00AEF0;">
        	<div class="container-fluid">
        		<div class="navbar-brand col-md-3 col-lg-2 me-0 px-3" style="text-align: left;"><?php $logoModule->display()?></div>
              	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                	<span class="navbar-toggler-icon"></span>
              	</button>         		
    			<div class="collapse navbar-collapse" id="navbarMenu">
    				<?php $mainMenuModule->display(["orient"=>"horizontal"]); ?>
    				<?php $settingsMenuModule->display(["orient"=>"dropdown"]); ?>
        		</div>
        	</div>
        </header>    			
        <div class="container-fluid">
          	<div class="row">
            	<nav id="sidebarMenu" class="sidebar_collapse_horizontal">
             		<div class="position-sticky pt-3 nav-sidebar">
    					<?php $sideMenuModule->display(["orient"=>"simple"]); ?>
    				</div>
    			</nav>
    			<main class="content_main">
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
		<?php require("Modules/Layout/m_Footer.php"); ?>
		<?php $modScriptImport->display(["output" => "js", "location" => "footer"]); ?>
	</body>
</html>    	