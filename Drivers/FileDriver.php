<?php
/**
 *File Driver
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

class FileDriver
{
	private $RESULT;
	private $arrLINES;
	private $INDEX;
	public $NUM_ROWS;

	
	public function __construct($FILENAME)
	{
		if($FILENAME != '')
			$this->Load($FILENAME);
	}
	
	public function __destruct()
	{
		
	}
	
	public function Process_Upload($FILENAME)
	{
		$this->arrLINES = array();
		$this->NUM_ROWS = 0;
		
		ini_set('upload_max_filesize',8192*1024);

		if ( !is_uploaded_file($FILENAME))
		{
			die("$FILENAME is not uploaded file! no can do.<br>");
		}
		else if(! $f=fopen($FILENAME,'r') )
		{
			print "can't open pipe $FILENAME";
		}
		else
		{
			set_time_limit(0);
				
			while($line=fgets($f,2048))
			{
				if (strlen($line)==0)
					continue;
				
				$this->arrLINES[$this->NUM_ROWS] = $line;
				
				//echo $line,"<br>";
				
				$this->NUM_ROWS++;			
			}
			
			$this->INDEX = 0;
		}
	}
	
	public function Fetch_Array()
	{
		return $this->arrLINES;
	}
	
	public function Fetch_line()
	{
		if($this->INDEX < $this->NUM_ROWS)
		{
			$str = $this->arrLINES[$this->INDEX];
			$this->INDEX++;
			return $str;
		}
		
		return 0;	
	}

	public function Close($fname)
	{
		unlink($fname);	
	}
	
	function Upload($TEMPFILE, $DEST)
	{
		if (move_uploaded_file($TEMPFILE, $DEST)) {
   			return 1;
		} else {
			return 0;
		}	
		
	}	
	
	function Delete($PATH)
	{
		unlink($PATH);	
	}
	
	static function List_Files($DIRECTORY_PATH, $FILTER_EXT="")
	{
		$arrFILES = scandir($DIRECTORY_PATH);

		if($FILTER_EXT=="")	return $arrFILES;
		
		for($i = 2; $i < count($arrFILES); $i++)
		{
			$file_ext = strtolower(substr(strchr($arrFILES[$i], "."), 1));		
			
			if($file_ext == $FILTER_EXT)
				$result[] = $arrFILES[$i];
		}

		return $result;
	}
	
}


?>