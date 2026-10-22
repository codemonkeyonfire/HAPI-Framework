<?php

abstract class AbstractCronModule
{
	protected $objDatabase;
	protected $objRegistry;
	protected $execAt;
	protected $enabled;
	
	abstract public function run($PARAMETERS="");
	abstract public function output($PARAMETERS="");
	
	public function __construct(&$REGISTRY="", &$DATABASE="")
	{
		$this->objDatabase = &$DATABASE;
		$this->objRegistry = &$REGISTRY;
		$this->execAt = array("minute" => "*", "hour" => "*", "day" => "*", "month" => "*", "weekday" => "*");
		$this->enabled = false;
	}
	
	public function is_enabled($VAL="")
	{		
		return $this->enabled;
	}
	
	public function exec_at()
	{
		return $this->execAt;
	}
	
	protected function set_execute($VAL)
	{
		$buffer = explode(" ", $VAL);
		
		$this->execAt["minute"] = $buffer[0];
		$this->execAt["hour"] = $buffer[1];
		$this->execAt["day"] = $buffer[2];
		$this->execAt["month"] = $buffer[3];
		$this->execAt["weekday"] = $buffer[4];
	}
	
	public function every_N_units($N="*", $UNITS="min", $STARTING="*", $ENDING ="*")
	{
		$map = array("min" => "minute", "h" => "hour", "d" => "day", "mth" => "month");
		$limits = array("min" => 60, "h" => 12, "d" => 31, "mnt" => 12);
		
		if($N <= $limits[$UNITS])
		{
			$E = "$STARTING";
			if($ENDING != "*") $E .= "-$ENDING"; 
			$this->execAt[$UNITS] = "$E/$N";
		}
	}
	
	public function for_set_of_unit($SET, $UNIT)
	{
		$this->execAt[$UNIT] = implode(",", $SET);
	}
	
	public function for_range_of_unit($START, $END, $UNIT)
	{
		$this->execAt[$UNIT] = "{$START}-{$END}";
	}
	
	public function hourly($AT_MINUTE="*")
	{
		$this->execAt["hour"] = "*";
		
		if(is_array($AT_MINUTE))
			$this->for_set_of_unit($AT_MINUTE, "minute");
		else 
			$this->execAt["minute"] = "$AT_MINUTE";
	}
	
	public function daily($AT_HOUR="*", $AT_MINUTE="*")
	{
		$this->execAt["day"] = "*";
		
		if(is_array($AT_HOUR))
			$this->for_set_of_unit($AT_HOUR, "hour");
		else
			$this->execAt["hour"] = "$AT_HOUR";
		
		if(is_array($AT_MINUTE))
			$this->for_set_of_unit($AT_MINUTE, "minute");
		else
			$this->execAt["minute"] = "$AT_MINUTE";
	}
	
	public function monthly($AT_DAY="*", $AT_HOUR="*", $AT_MINUTE="*")
	{
		$this->execAt["month"] = "*";
		
		if(is_array($AT_DAY))
			$this->for_set_of_unit($AT_DAY, "day");
		else
			$this->execAt["day"] = "$AT_DAY";
		
		$this->execAt["hour"] = "$AT_HOUR";
		$this->execAt["minute"] = "$AT_MINUTE";
	}
	
	public function execute_now($TIME_STAMP)
	{
		$units =  array("minute", "hour", "day", "month", "weekday");
		$criteria = array("minute" => false, "hour" => false, "day" => false, "month" => false, "weekday" => false);
		
		foreach($units as $unit)
		{
			if($this->execAt[$unit] != "*")
			{
				if($unit == "minute")
					$now = intval(date("i", $TIME_STAMP));
				
				if($unit == "hour")
					$now = intval(date("G", $TIME_STAMP));
				
				if($unit == "day")
					$now = intval(date("j", $TIME_STAMP));
				
				if($unit == "month")
					$now = intval(date("n", $TIME_STAMP));
				
				if($unit == "weekday")
					$now = intval(date("w", $TIME_STAMP));
				
				if(strpos($this->execAt[$unit], ",") !== false)
				{
					$buffer = explode(",", $this->execAt[$unit]);
					foreach ($buffer as $tmp) 
					{
						if(strpos($tmp, "-") !== false)
						{
							$buffer2 = explode("-", $tmp);
							$start = intval($buffer2[0]); $end = intval($buffer2[1]);
							if($start <= $now && $end >= $now)
								$criteria[$unit] = true;
						}else{
							if($tmp == $now)
								$criteria[$unit] = true;
						}
					}
				}
				elseif(strpos($this->execAt[$unit], "/") !== false)
				{
					$buffer = explode("/", $this->execAt[$unit]);
					$range = $buffer[0]; $interval = intval($buffer[1]);
					$buffer = explode("-", $range);
					$start = intval($buffer[0]); $end = intval($buffer[1]);
					
					if(($buffer[0] == "*" || $start <= $now) && (empty($end) || $end >= $now) && ($now % $interval) == 0)
						$criteria[$unit] = true;
					
				}elseif (strpos($this->execAt[$unit], "-") !== false){
					$buffer = explode("-", $this->execAt[$unit]);
					$start = intval($buffer[0]); $end = intval($buffer[1]);
					
					if($start <= $now && $end >= $now)
						$criteria[$unit] = true;
				}else{
					if($this->execAt[$unit] == $now)
						$criteria[$unit] = true;
				}
			}else{
				$criteria[$unit] = true;
			}
		}
		
		return $criteria["minute"] && $criteria["hour"] && $criteria["day"] && $criteria["month"] && $criteria["weekday"];
	}
	
	protected function dump_array($ARRAY, $DIE=false)
	{
		if($DIE)
			die("<pre>".print_r($ARRAY, true)."</pre>");
		
		echo "<pre>".print_r($ARRAY, true)."</pre>";
	}
}
?>