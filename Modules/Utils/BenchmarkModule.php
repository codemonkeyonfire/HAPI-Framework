<?php

	$time_end = microtime(true);
	$time = $time_end - $time_start;
	echo "<div style='font-size:10px; position: absolute; top: 0px; left: 90%; width: 150px;'>[<span style='color:#ff0000;'>$time sec</span>]</div>";
	if($time > 10.0)
	{
		$f = fopen("slow_pages.log", "a");
		fwrite($f, "$time - $CMD - ".date("Y-m-d H:i:s")."\n");
		fclose($f);
	}	
	
?>