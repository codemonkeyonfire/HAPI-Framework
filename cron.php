<?php
header( 'Content-type: text/html; charset=utf-8' );

ob_start();

console_output("start");
	
$bit = (isset($_REQUEST["dbug"]))?"on":"off";
ini_set("display_errors", "on");
ini_set("error_reporting", E_ALL & ~E_NOTICE);

$modInit = new \Modules\General\InitModule();
$modInit->run($_REQUEST);

$objRegistry = $modInit->get_registry();
$objDatabase = $modInit->get_database();

$WEBSITE_ROOT = $_SERVER['HTTP_HOST'];

// execute the requested function
$cronfiles = scandir("./Modules/Crons");

foreach ($cronfiles as $filename)
{
	$file_ext = strtolower(substr($filename, strpos($filename, ".")));

	if($file_ext == ".php")
	{
		console_output("found ".$filename);
		
		require("Modules/Crons/{$filename}");
		$start = strpos($filename, "_") + 1;
		$end = strpos($filename, ".");
		$CronClass = substr($filename, $start, $end - $start)."CronModule";
		console_output("loading ".$CronClass);
		$CronObject = new $CronClass($objRegistry, $objDatabase);
		
		if($CronObject->is_enabled())
		{
			$test = strtotime("now");
			for($i = 0; $i < 120; $i++)
			{				
				if($CronObject->execute_now($test))
				{
					console_output(date("D j", $test)." executed ".$CronClass);
				}else{
					console_output(date("D j", $test)." not executed");
				}
				$test = strtotime("+1 day", $test);
			}
		}
	}
}

	//session_write_close();
ob_end_flush();

function console_output($TEXT)
{
	echo "=>{$TEXT} <br>"; flush(); ob_flush();
}