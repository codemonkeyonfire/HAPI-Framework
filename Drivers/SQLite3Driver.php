<?php
/**
 * sqllite3 Driver
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

use SQLite3;

class SQLite3Driver
{
	public $DB;
	public $RESULT;
	public $NUM_ROWS;
	public $INSERT_ID;
	public $AFFECTED_ROWS;

	
	public function __construct($DB_PATH="")
	{
		if($DB_PATH != "")
		{
			$this->DB = new SQLite3($DB_PATH);
			
			if ($this->DB->lastErrorCode()) 
			{
	    		printf("Connect failed: %s\n", $this->DB->lastErrorMsg());
	    		exit();
			}		
		}else{
			$this->DB = NULL;
		}
	}
	
	public function __destruct()
	{
		$this->DB->close();
		
	}
	
	public function Close()
	{
		$this->DB->close();
	}
	
	public function Open($DB_PATH)
	{
		$this->DB = new SQLite3($DB_PATH);
		
		if ($this->DB->lastErrorCode()) 
		{
    		printf("Connect failed: %s\n", $this->DB->lastErrorMsg());
    		exit();
		}			
	}
	
	public function Query($strSQL)
	{
		if(strstr($SQL, "INSERT"))
	    {
    		$this->DB->exec($strSQL) or die("Error: ".$this->DB->lastErrorMsg()."<br>".$strSQL);
       		$this->INSERT_ID = $this->DB->lastInsertRowID();
	    }
        elseif(strstr($strSQL, "UPDATE") || strstr($SQL, "DELETE"))
        {
        	$this->DB->exec($strSQL) or die("Error: ".$this->DB->lastErrorMsg()."<br>".$strSQL);
        	$this->AFFECTED_ROWS = $this->DB->changes();
        }
        elseif(strstr($strSQL, "SELECT"))
        {
        	$this->RESULT = $this->DB->query($strSQL);
        	if(!$this->RESULT) 
        		die("Error: ".$this->DB->lastErrorMsg()."<br>".$strSQL);

        }else{
      	 
        	if(!$this->DB->exec($strSQL))
        		 die("Error: ".$this->DB->lastErrorMsg()."<br>".$strSQL);
        }
        
        return $this->RESULT;
	}
	
	public function Fetch_Single()
	{
			return $this->RESULT->fetchArray();
	}	
	
	public function Fetch_Arrays($STYLE = 'assoc')
	{
		$arrRETURN = array();
		$this->NUM_ROWS = 0;
		
		$TYPE = ($STYLE == 'assoc')?SQLITE3_ASSOC:SQLITE3_NUM;
		
		while ($DATA = $this->RESULT->fetchArray($TYPE)) 
		{
			$arrRETURN[] = $DATA;	
			$this->NUM_ROWS++;
		}
		
		return $arrRETURN;
	}
	
	public function Fetch_Objects()
	{
		die("Fetch_Objects not supported by SqlLite3");
		
		return 0;	
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
	
}


?>