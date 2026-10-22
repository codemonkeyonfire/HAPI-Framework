<?php
/**
 * Mysql Driver
 *
 * formats config varibles and reads values from that format
 * @author Carlos Ferrera
 * @version 1.0
 * @package configuration
 */
/**
 * The first example class, this is in the same package as the
 * procedural stuff in the start of the file
 * @package configuration
 * @subpackage classes
 */
namespace Drivers;

use Exception;
use mysqli;

class MysqliDriver
{
	private $DB;
	private $RESULT;
	public $NUM_ROWS;
	public $INSERT_ID;
	public $AFFECTED_ROWS;

	
	public function __construct()
	{

	}
	
	public function connect($SERVER, $USER, $PASSWD, $DB_NAME)
	{
		$this->DB = new mysqli($SERVER, $USER, $PASSWD, $DB_NAME);
		
		if ($this->DB->connect_error) 
		{
    		printf("Connect failed: %s\n", $this->DB->connect_error);
    		exit();
		}		

	}
	
	public function __destruct()
	{
		$this->DB->close();
		
	}
	
	public function Query($strSQL)
	{
		$BENCHMARK = 1;
		
		if($BENCHMARK) $time_start = microtime(true);
		try{
    		if(preg_match("/\bINSERT\b/i", $strSQL))
    	    {
        		$this->DB->query($strSQL) or die("Error: ".$this->DB->error."<br>".$strSQL);
           		$this->INSERT_ID = $this->DB->insert_id;
    	    }
            elseif(preg_match("/\bUPDATE\b/i", $strSQL) || preg_match("/\bDELETE\b/i", $strSQL))
            {
            	$this->DB->query($strSQL) or die("Error: ".$this->DB->error."<br>".$strSQL);
            	$this->AFFECTED_ROWS = $this->DB->affected_rows;
            }else{
            	$this->RESULT = $this->DB->query($strSQL) or die("Error: ".$this->DB->error."<br>".$strSQL);
            	$this->NUM_ROWS = $this->RESULT->num_rows;
            }
		}catch (Exception $e)
		{
		    die("Error: ".$e->getMessage()."<br>".$strSQL);
		}
        
        if($BENCHMARK)
        {
        	$time_end = microtime(true);
			$time = $time_end - $time_start;

			if($time > 0.5)
			{
				$f = fopen("slow_queries.log", "a");
				fwrite($f, "$time - $strSQL - ".date("Y-m-d H:i:s")."\n");
				fclose($f);
			}
        }
		
        return $this->RESULT;
	}
	
	public function Fetch_Single($TYPE='object')
	{
		if($TYPE=="array")
			return $this->RESULT->fetch_array(MYSQLI_ASSOC);
		else 	
			return  $this->RESULT->fetch_object();
			
	}
	
	public function Fetch_Arrays($STYLE = 'assoc')
	{
		$arrRETURN = array();
		$this->NUM_ROWS = 0;
		
		$TYPE = ($STYLE == 'assoc')?MYSQLI_ASSOC:MYSQL_NUM;
		
		while ($DATA = $this->RESULT->fetch_array($TYPE)) 
		{
			$arrRETURN[] = $DATA;	
			$this->NUM_ROWS++;
		}
		
		return $arrRETURN;
	}
	
	public function Fetch_Objects()
	{
		$arrRETURN = array();
		$this->NUM_ROWS = 0;
		
		while ($DATA = $this->RESULT->fetch_object()) 
		{
			$arrRETURN[] = $DATA;
			$this->NUM_ROWS++;	
		}
		
		return $arrRETURN;	
	}
	
	public function Lock_Table($TABLE)
	{
		$SQL = "LOCK TABLE $TABLE WRITE";
		$this->Query($SQL);
	}
	
	public function Unlock_Table()
	{
		$SQL = "UNLOCK TABLES";
		$this->Query($SQL);		
	}	
	
	public function Escape_Special($STR)
	{
		return $this->DB->escape_string($STR);	
	}
	
	// create separate functions for partitioned tables 
	
	public function Query_Partitioned($SQL, $KEY="")
	{
	    $pattern = "/#(.*?)#/";
    	
	    if(!preg_match($pattern, $SQL, $matches))
	    	die("!!!! must mark table name with #");
  	    	
    	
    	$Table = $matches[1];
    	   	
    	if($KEY == "")
    	{
    		$tSql = preg_replace("/#$Table#/", $Table."_X0", $SQL);
    		for($i = 1; $i<10; $i++)
    		{
    			$tSql .= " UNION ALL ".preg_replace("/#$Table#/", $Table."_X".$i, $SQL);
    			    		
    		}
    	}else{
    		$hash = substr($KEY, -1);
    		$tSql = preg_replace("/#$Table#/", $Table."_X".$hash, $SQL);
    	}

    	$this->Query($tSql);

	}
		
	public function Expand($TABLE, $KEY_NAME, $MOVE=0)
	{
		$sql = "SELECT * FROM $TABLE";
		$res = $this->Query($sql);
		
		while ($arrData = $res->fetch_assoc()) 
		{
			$arrKeys = array();
			$arrVals = array();
			
			foreach ($arrData as $key => $val)
			{
				if($key!="ReferenceID")
				{
					$arrKeys[] = ($key=="ID")?"ReferenceID":$key;
					$arrVals[] = "'$val'";
				}
			}	
			$tSQL =  "INSERT INTO #$TABLE# (".implode(", ", $arrKeys).") VALUES (".implode(", ", $arrVals).")";
			$this->Query_Partitioned($tSQL, $arrData[$KEY_NAME]);
		}		
		
		if($MOVE)
		{
			$sql = "DELETE FROM $TABLE";
			$this->Query($sql);			
		}
	}
	
	public function Collapse($TABLE)
	{
		
	}
}


?>