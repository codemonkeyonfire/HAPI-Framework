<?php
/**
 * File Parsing Driver
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

class SchemaDriver
{
	private $strFileName = "schema.txt";
	private $strSchemaName;
	private $arrTables;
	
	
	//  must end with a SCHEMA tag
	public function __construct($FILENAME="")
	{
		if($FILENAME != '')
			$this->strFileName = $FILENAME;
			
		$f=fopen($this->strFileName,'r');
		
		$Schema = "";
		$Table = "";
		$Column = "";

		while($line=fgets($f,2048))
		{
			$item = explode(":", $line);
			
			$option = strtolower($item[0]);
			switch($option)
			{
				case "schema":
				    if($Schema != "")
				    {		
				    	$this->strSchemaName = $Schema;
				    	$this->arrTables = $arrTables;
				    }		
				
					$Schema = $item[1];	
					$schemaComment = $item[2];
					$Table = "";					
					break;
				case "table":
				    if($Table != "")
				    {		
				    	$arrTables[$Table]["columns"] = $arrColumns;
				    	unset($arrColumns);
				    	unset($arrColumn);
				    }					
				
					$Table = $item[1];	
					$tableComment = $item[2];	
					$Column = "";				
					break;
				case "column":
				    if($Column != "")
				    {
				    	$arrColumns[$Column] = $arrColumn;
				    	unset($arrColumn);
				    }
				    
					$Column = $item[1];
					break;		
				default:
					if($Column != "")	
						$arrColumn[$item[0]] = $item[1];						
			}
		}		
	}
	
	public function Generate_Create_SQL()
	{
		
	}
}
?>