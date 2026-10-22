<?php
 /*************************************************************************
 * 
 *  (c)2011 Carlos Ferrera
 *  All Rights Reserved.
 * 
 * NOTICE:  
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Carlos Ferrera.
 *
 * Email: irondogg@gmail.com
 */
namespace Handlers\Util;

class DateHandler
{

	public function __construct()
	{
		
	}
	
	static function slash_format($dash_date)
	{
		$junk = explode(" ",$dash_date);
		$pieces = explode("-",$junk[0]);
		if(isset($pieces[2]))
		{
	
			$slash_date = array($pieces[1], $pieces[2], $pieces[0]);
			$return_str = implode("/", $slash_date);
			return $return_str." ".$junk[1];
		}
		return $dash_date;
	}
	
	static function timestamp_format($dash_date)
	{
		$junk = explode(" ",$dash_date);
		$date_pieces = explode("-",$junk[0]);
		$time_pieces = explode(":",$junk[1]);
		
		return mktime($time_pieces[0], $time_pieces[1], $time_pieces[2], $date_pieces[1], $date_pieces[2], $date_pieces[0]);
	}	
	
	static function dash_format($slash_date)
	{
		$slash_date = rtrim($slash_date);
	  	$pieces = explode("/", $slash_date);
	  	$dash_date = array($pieces[2], $pieces[0], $pieces[1]);
	  	$return_str = implode("-",$dash_date);
	  	return $return_str;
	}
	
	static function from_long_string($str)
	{
		$y = substr($str, 0, 4);
		$m = substr($str, 4, 2);
		$d = substr($str, 6, 2);
	
		return "$m/$d/$y";
	}
	
	static function to_long_string($slash_date)
	{
		list($m, $d, $y) = explode("/", $slash_date);
		$date = (strlen($y) < 3)?"0".$y:$y;
		$date = (strlen($date) < 4)?"2".$date:$date;
		$date .= (strlen($m) < 2)?"0".$m:$m;
		$date .= (strlen($d) < 2)?"0".$d:$d;	
		return $date."0001";
	}

	static function to_12_hours($TIME24)
	{
		list($hours, $minutes, $seconds) = explode(":", $TIME24);
		
		$hrs = intval($hours);
		
		$meridian = "AM";
		if($hrs > 11)
		{
			if($hrs != 24)
				$meridian = "PM";
			
			if($hrs > 12) $hrs = $hrs - 12;
		}
		
		return $hrs.":".$minutes.$meridian;
	}
	
	static function to_24_hours($TIME12)
	{
		list($hours, $rest) = explode(":", $TIME24);
		
		$hrs = intval($hours);
		$min = substr($rest, 0, 2);
		$mrd= strtoupper(substr($rest, -2));
		
		if($mrd == "PM" && $hrs < 12)
			$hrs = $hrs + 12;
		
		if($mrd == "AM" && $hrs == 12)
			$hrs = 0;
		
		$result = ($hrs < 10)?"0":"";
		$result .= $hrs;
		$result .= ":";
		$result .= $min;
		$result .= ":00";
			
		return $result;
	}
	
	static function month_view_dates($MONTH, $YEAR, $FORMAT="")
	{
		$seconds_per_day = 60 * 60 * 24;
		
		$days_offset = date("w", mktime(0, 0, 0, $MONTH, 1, $YEAR));
		
		$ts_day = mktime(0, 0, 0, $MONTH, 1 - $days_offset, $YEAR);
		
		for($w = 0; $w < 6; $w++)
			for($d = 0; $d < 7; $d++)
			{
				if(empty($FORMAT))
					$month_dates[$w][$d]["date"] = $ts_day;
				else 
					$month_dates[$w][$d]["date"] = date($FORMAT, $ts_day);
					
					$month_dates[$w][$d]["status"] = (date("n", $ts_day) == $MONTH)?"in":"out";
					
					if($ts_day == mktime(0, 0, 0, date("n"), date("j"), date("Y")))
						$month_dates[$w][$d]["status"] = "today";
				
				$ts_day += $seconds_per_day;
			}
			
		return $month_dates;
	}
	
	public static function freshness($DATE)
	{
		$now = mktime();
		$created = strtotime($DATE);
		$tmp = $now - $created;
	
		$mins = round($tmp / 60);
		$hrs = round($mins / 60);
		$days = round($hrs / 24);
		if($mins < 60)
			$result = "{$mins} mins";
		elseif($hrs < 24)
		$result = "{$hrs} hrs";
		else
			$result = "{$days} days";
	
		return $result;
	}
}
?>