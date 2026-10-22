<?php
require("autoloader.php");

ob_start();
	
$bit = (isset($_REQUEST["dbug"]))?"on":"off";
ini_set("display_errors", $bit);
ini_set("error_reporting", E_ALL & ~E_NOTICE);

session_start();

$modInit = new \Modules\General\InitModule();

$params = $_REQUEST;
//$params["payload"] = file_get_contents('php://input');

$modInit->run($params);

$objRegistry = &$modInit->get_registry();
$objDatabase = &$modInit->get_database();

if(empty($params["key"]))
{
    $modSecurity = new \Modules\Utils\SecurityModule($objRegistry, $objDatabase);
    $modSecurity->run();
    
    $un = $modSecurity->get_username();
    if($un == "")
    {
        die("access forbidden: ".$un);
    }
}

$WEBSITE_ROOT = $_SERVER['HTTP_HOST'];

// execute the requested function
$OBJECT = $modInit->get_main_module();
$VARS = $objRegistry->Get_Value("REQUEST:DATA");

$ApiClass = ucfirst($OBJECT)."Module";

require("Modules/API/{$OBJECT}Module.php");
$ApiObject = new $ApiClass($objRegistry, $objDatabase);

$ApiObject->run($VARS);

$ApiObject->display();

ob_end_flush();