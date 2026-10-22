<?php
/**
 * sqllite Driver
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

use PDO;
use PDOException;

class SQLiteDriver
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
			try 
			{		
				$this->DB = new PDO("sqlite:$DB_PATH");
			} catch (PDOException $e) {
			    print "Error!: " . $e->getMessage() . "<br/>";
			    die();
			}			
		}else{
			$this->DB = NULL;
		}
	}
	
	public function __destruct()
	{
		$this->DB = null;
		
	}
	
	public function Close()
	{
		$this->DB = null;
	}
	
	public function Open($DB_PATH)
	{
		try 
		{		
			$this->DB = new PDO("sqlite:$DB_PATH");
		} catch (PDOException $e) {
		    print "Error!: " . $e->getMessage() . "<br/>";
		    die();
		}			
	}
	
	public function Query($strSQL)
	{
		$this->RESULT = null;

		try 
		{
			if(preg_match("/\bINSERT\b/i", $strSQL))
		    {
	    		$this->DB->exec($strSQL);
	       		$this->INSERT_ID = $this->DB->lastInsertId();
		    }
	     	elseif(preg_match("/\bUPDATE\b/i", $strSQL) || preg_match("/\bDELETE\b/i", $strSQL))
	        {
	        	$this->AFFECTED_ROWS = $this->DB->exec($strSQL);
	        }
	        elseif(preg_match("/\bSELECT\b/i", $strSQL))
	        {
	        	$this->RESULT = $this->DB->query($strSQL) or die("Error: $error <br> $strSQL");
	
	        }else{
	      	 
	        	$this->DB->exec($strSQL);       		
	        }
		} catch (PDOException $e) {
		    print "Error!: " . $e->getMessage() . "<br/>";
		    die();
		}        

        return $this->RESULT;
	}
	
	public function Fetch_Single()
	{
		if ($this->RESULT)
			return $this->RESULT->fetch(PDO::FETCH_ASSOC);
		else 	
			die("Result not set =>".$this->RESULT);
	}	
	
	public function Fetch_Arrays($STYLE = 'assoc')
	{
		$arrRETURN = array();
		$this->NUM_ROWS = 0;
		
		$TYPE = ($STYLE == 'assoc')?SQLITE3_ASSOC:SQLITE3_NUM;
		
		while ($DATA = $this->RESULT->fetch(PDO::FETCH_ASSOC)) 
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
				
		while ($DATA = $this->RESULT->fetch(PDO::FETCH_OBJ)) 
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
	
}


?>