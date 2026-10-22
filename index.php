<?php

    require("autoloader.php");
    
    $bit = (isset($_GET["dbug"]))?1:0;
    
    ini_set("display_errors", $bit);
    ini_set("error_reporting", E_ALL & ~E_NOTICE);
    
    session_start();

	$BENCHMARK = 1;
	if($BENCHMARK) $time_start = microtime(true);
	// ADD ROUTER HERE
	
	$modInit = new \Modules\General\InitModule();
	$modInit->run($_REQUEST);

	$objRegistry = $modInit->get_registry();
	$objDatabase = $modInit->get_database();

	$modSecurity = new \Modules\Utils\SecurityModule($objRegistry, $objDatabase);
	$modSecurity->run();

	$app = $objRegistry->Get_Value("SITE:APP_NAME");
	$mod = $objRegistry->Get_Value("SITE:MAIN_MODULE");
	
	$modulePath = "Modules/{$app}/{$mod}Module.php";
	
	if (file_exists($modulePath)) 
	{
	    if($bit) echo "The file $modulePath exists<br>";
	    
	    require($modulePath);
	    $mainModCons = $mod."Module";
	} else {
	    if($bit) echo "The file $modulePath does not exist<br>";
	    
	    $modulePath = "Modules/{$app}/".ucfirst($app).ucfirst($mod)."Module.php";
	    if (file_exists($modulePath)) 
	    {
	        if($bit) echo "The file $modulePath exists<br>";
	        
	        require($modulePath);
	        $mainModCons = ucfirst($app).ucfirst($mod)."Module";
	    } else {
	        echo "The file $modulePath does not exist<br>";
	        die("ERROR: module file not found");
	    }
	}	
	
	
	$mainModule = new $mainModCons($objRegistry, $objDatabase);

	$mainModule->run();

	$modScriptImport = new \Modules\Layout\ScriptImportModule($objRegistry);
	$modScriptImport->run();

	require("Views/pageLayout/".$mainModule->template."View.php");
	
	//session_write_close();
	ob_end_flush();
?>