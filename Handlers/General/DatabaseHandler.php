<?php
namespace Handlers\General;

class DatabaseHandler
{
	private $objDB = "";
	private $hashedTables;
	
	public function __construct($SETTINGS)
	{
		$server = $SETTINGS["Server"];
		$user = $SETTINGS["User"];
		$password = $SETTINGS["Password"];
		$db = $SETTINGS["Database"];
		
		$this->objDB = new \Drivers\MysqliDriver();
		$this->objDB->connect($server, $user, $password, $db);
	}	
	
	
	function Query($SQL)
	{
		return $this->objDB->Query($SQL);
	}	
	 
	function Fetch_Single($strTYPE='object')
	{
		return $this->objDB->Fetch_Single($strTYPE);
	}
		 
	function Fetch_Arrays($strSTYLE = 'assoc')
	{
		return $this->objDB->Fetch_Arrays($strSTYLE);
	}
		 
	function Fetch_Objects()
	{
		return $this->objDB->Fetch_Objects();
	}
		  
	function Lock_Table($strTABLE)
	{
		return $this->objDB->Lock_Table($strTABLE);
	}
		   
	function Unlock_Table()
	{
		return $this->objDB->Unlock_Table();
	}
		    
	function Escape_Special($VAL)
	{
		return $this->objDB->Escape_Special($VAL);
	}	
	
	function query_hashed_tables()
	{
	}
	
	function update_hashed_tables()
	{
	}

	function insert_hashed_tables()
	{
	}	
	
	function edit_hashed_tables()
	{
		
	}
	
	function Get_NumRows()
	{
		return $this->objDB->NUM_ROWS;			
	}

	function Get_LastInsert()
	{
		return $this->objDB->INSERT_ID;			
	}
	
	function Get_Affected()
	{
		return $this->objDB->AFFECTED_ROWS;		
	}
}

?>