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
namespace Handlers\General;

abstract class BaseHandler
{
	protected $objDB;
	protected $objDEBUG;
	protected $objRegistry;
	public $DB_TABLE;
	public $DB_FIELDS;
	protected $DB_FIELD_TYPES;
	protected $arrRECORD_WORKBENCH;
	protected $arrRECORDS;					// only used as a buffer
	protected $numINDEX;
	protected $numCOUNT;
	protected $numPAGE;
	protected $numPER_PAGE;
	protected $numTOTAL;
	protected $numTOTAL_PAGES;
	protected $SORT_BY;
	protected $SORT_DIRC;	
	protected $LAST_SQL;
	
	public function __construct(&$DATABASE, &$SETTINGS="")
	{
		//  set static settings here 
		
		$this->objDB = &$DATABASE;
		$this->objRegistry = &$SETTINGS;
		
		$this->numCOUNT = 0;
		$this->numPER_PAGE = 30;
		$this->SORT_BY = "ID";
		$this->SORT_DIRC = "DESC";	
		$this->STATE = "empty";	
		
		foreach ($this->DB_FIELDS as $field)
		{
		    $this->DB_FIELD_TYPES[$field["name"]] = $field["type"];
		}
	}	
	
    public function __toString()
    {
        return $this->STATE;
    }	
	
	public function __invoke($ACTION)
	{
		switch($ACTION)
		{
			case "dump":
				unset($this->arrRECORD_WORKBENCH);
				unset($this->arrRECORDS);
				$this->STATE = "empty";	
				break;
		
		}
	}
	
	public function Load($ID)
	{
		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE ID = $ID";
		$this->objDB->Query($strSQL);		

		$arrTEMP = $this->objDB->Fetch_Arrays();	
		
		$this->arrRECORD_WORKBENCH = $arrTEMP[0];		
		
		$this->numINDEX = 0;
		$this->numCOUNT = 0;
		
		$this->STATE = "loaded";	

	}	
	
	public function Load_Secure($SRID)
	{
		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE SRID = '$SRID'";
		$this->objDB->Query($strSQL);		

		$arrTEMP = $this->objDB->Fetch_Arrays();	
		
		$this->arrRECORD_WORKBENCH = $arrTEMP[0];		
		
		$this->numINDEX = 0;
		$this->numCOUNT = 0;
		
		$this->STATE = "loaded";	

	}
	
	public function Load_All()
	{
		$arrTEMP = array();
		$strSQL = "SELECT * FROM $this->DB_TABLE ORDER BY $this->SORT_BY $this->SORT_DIRC";
		$this->objDB->Query($strSQL);		

		$arrTEMP = $this->objDB->Fetch_Arrays();	
		
		$this->arrRECORDS = $arrTEMP;	
		$this->arrRECORD_WORKBENCH = $arrTEMP[0];			
		
		$this->numINDEX = 0;
		$this->numCOUNT = count($arrTEMP);
		
		$this->STATE = "loaded";
		
		return $this->objDB->Get_NumRows();
	}

	public function Load_Page($PAGE_NUM, $PER_PAGE=100, $MATCH="ALL")
	{
	    $OPR = ($MATCH=="ANY")?"OR":"AND";
	    $arrTEMP = [];

	    $start = $PER_PAGE * ($PAGE_NUM - 1);
		
		foreach ($this->arrRECORD_WORKBENCH as $KEY => $VAL)
		{
		    if(strpos($VAL, "%") === false){
		        $arrTEMP[] = "$KEY = '$VAL'";
		    }else{
		        $arrTEMP[] = "$KEY LIKE '$VAL'";
		    }
		}
		
		$strWHERE = implode(" $OPR ", $arrTEMP);
		
		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE {$strWHERE} ORDER BY $this->SORT_BY $this->SORT_DIRC LIMIT {$start}, {$PER_PAGE}";
		$this->objDB->Query($strSQL);		

		$arrTEMP = $this->objDB->Fetch_Arrays();	
		
		$this->arrRECORDS = $arrTEMP;	
		$this->arrRECORD_WORKBENCH = $arrTEMP[0];			
		
		$this->numINDEX = 0;
		$this->numCOUNT = count($arrTEMP);
	
		$this->STATE = "loaded";
		
		return $this->objDB->Get_NumRows();
	}
	
	public function Load_Set($FIELD, $IDs)
	{
		$arrTEMP = array();

		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE $FIELD IN ($IDs) ORDER BY $this->SORT_BY $this->SORT_DIRC";

		$this->objDB->Query($strSQL);		

		$arrTEMP = $this->objDB->Fetch_Arrays();	
		
		$this->arrRECORDS = $arrTEMP;	
		$this->arrRECORD_WORKBENCH = $arrTEMP[0];			
		
		$this->numINDEX = 0;
		$this->numCOUNT = count($arrTEMP);
		
		$this->STATE = "loaded";
		
		return $this->objDB->Get_NumRows();		
	}	
	
	public function Load_Range($FIELD, $START='', $END='')
	{
	    $arrTEMP = array();
	    
	    $crit = [];
	    
	    if($START != '')
	    {
	        $crit[] = "{$FIELD} >= {$START}";
	    }
	    
	    if($END != '')
	    {
	        $crit[] = "{$FIELD} < {$END}";
	    }
	    
	    $critSTR = implode(" AND ", $crit);
	    
	    $strSQL = "SELECT * FROM $this->DB_TABLE WHERE {$critSTR} ORDER BY $this->SORT_BY $this->SORT_DIRC";
	    $this->objDB->Query($strSQL);
	    
	    $arrTEMP = $this->objDB->Fetch_Arrays();
	    
	    $this->arrRECORDS = $arrTEMP;
	    $this->arrRECORD_WORKBENCH = $arrTEMP[0];
	    
	    $this->numINDEX = 0;
	    $this->numCOUNT = count($arrTEMP);
	    
	    $this->STATE = "loaded";
	    
	    return $this->objDB->Get_NumRows();
	}
	
	public function Load_Last()
	{
	    $arrTEMP = array();
	    
	    $strSQL = "SELECT * FROM $this->DB_TABLE ORDER BY ID DESC LIMIT 1";
	    $this->objDB->Query($strSQL);
	    
	    $arrTEMP = $this->objDB->Fetch_Arrays();
	    
	    $this->arrRECORDS = $arrTEMP;
	    $this->arrRECORD_WORKBENCH = $arrTEMP[0];
	    
	    $this->numINDEX = 0;
	    $this->numCOUNT = count($arrTEMP);
	    
	    $this->STATE = "loaded";
	    
	    return $this->objDB->Get_NumRows();
	}
	
	public function Search($MATCH='ALL', $EXPLICIT="", $DEBUG=false)
	{
		$OPR = ($MATCH=="ANY")?"OR":"AND";
	
		foreach ($this->arrRECORD_WORKBENCH as $key => $val)	
		{
			if(isset($val))
			{
			    $val= htmlentities($this->objDB->Escape_Special($val));
			    if($VAL[0] == "~"){
			        $arrTEMP[] = "$key = ".substr($val, 1);
			    }elseif(strpos($val, "%") === false){
					$arrTEMP[] = (is_string($val) || $val == "")?"{$key} = '{$val}'":"{$key} = {$val}";				
				}else{
					$arrTEMP[] = "{$key} LIKE '{$val}'";
				}
			}
		}
		
		$strOPT = implode(" $OPR ", $arrTEMP);
		
		if($EXPLICIT != "")
		{
		    $strOPT = "";
		    $criteria = explode(";", $EXPLICIT);
		    foreach ($criteria as $condition)
		    {
		        $element = str_replace(" ", "", $condition);
		        $element = str_replace(",", " ", $element);
		        
		        if(!empty($strOPT)) $strOPT .= $OPR;
		            
		        $strOPT .= " {$element} ";
		    }
		}
		
		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE ($strOPT) ORDER BY $this->SORT_BY $this->SORT_DIRC";
		if($DEBUG) die("=>".$strSQL);
		$this->objDB->Query($strSQL);		

		$arrTEMP = $this->objDB->Fetch_Arrays();	
		
		$this->arrRECORDS = $arrTEMP;	
		if(!empty($arrTEMP))
			$this->arrRECORD_WORKBENCH = $arrTEMP[0];			
		
		$this->numINDEX = 0;
		$this->numCOUNT = count($arrTEMP);
		
		$this->STATE = "loaded";
		
		return $this->objDB->Get_NumRows();		
	}	
	
	public function Fetch($DBUG=false)
	{
	    if($DBUG) die("=>{$this->numINDEX} < {$this->numCOUNT}");
		if($this->numINDEX < $this->numCOUNT)
		{
		    $this->arrRECORD_WORKBENCH = $this->arrRECORDS[$this->numINDEX];	
			$this->numINDEX++;
			return $this->numINDEX;
		}
		
		return 0;	
	}	
	
	public function Fetch_All()
	{
	    if(empty($this->arrRECORDS))
	    {
	        return $this->arrRECORD_WORKBENCH;
	    }
	    return $this->arrRECORDS;
	}
		
	public function Commit($DEBUG=0)
	{
		$arrKEYS = array();
		$arrVALUES = array();
		
		$srID = "";
				
		if($this->arrRECORD_WORKBENCH["ID"] || $this->arrRECORD_WORKBENCH["SRID"])
		{
			foreach ($this->arrRECORD_WORKBENCH as $key => $val)	
			{
				if($key!= "ID" && $KEY != "SRID" && $key!= "Created")
				{
					$val = $this->objDB->Escape_Special($val);
					if($this->DB_FIELD_TYPES[$key] == "bool" || $this->DB_FIELD_TYPES[$key] == "integer")
					{
					    $arrVALUES[] = "$key= $val";		
					}else{
					    $arrVALUES[] = "$key= '$val'";		
					}									
				}
			}
			
			$optID = ($this->arrRECORD_WORKBENCH["SRID"])?"SRID = '".$this->arrRECORD_WORKBENCH["SRID"]."'":"ID = ".$this->arrRECORD_WORKBENCH["ID"];
			
			$strVALUES = implode(",", $arrVALUES);
			
			$strSQL ="UPDATE $this->DB_TABLE SET $strVALUES WHERE $optID";
					
		}else {
			foreach ($this->arrRECORD_WORKBENCH as $key => $val)	
			{
				if($key!= "ID" && $key!= "SRID" && $key!= "Created")
				{
				    $val = $this->objDB->Escape_Special($val);
					$arrKEYS[] = "$key ";	
					if($this->DB_FIELD_TYPES[$key] == "bool" || $this->DB_FIELD_TYPES[$key] == "integer")
					{
					    $arrVALUES[] = "$val";
					}else{
					    $arrVALUES[] = "'$val'";
					}	
				}
			}
			
			$strKEYS = implode(",", $arrKEYS);
			$strVALUES = implode(",", $arrVALUES);
			
			list($usec, $sec) = explode(" ", microtime());
			$seed = $sec + $usec * 1000000;
			mt_srand($seed);
			$startPos = mt_rand(0, 30);
			
			$srID = substr(sha1($usec."monkeyBallz"), $startPos, 10);
			$this->arrRECORD_WORKBENCH["SRID"] = $srID;
			
			$strSQL ="INSERT INTO $this->DB_TABLE ($strKEYS, Created, SRID) VALUES ($strVALUES, '".date("Y-m-d H:i:s")."', '$srID')";	
				
		}

		if($DEBUG)
		{
			if($DEBUG == 1)
			{
				echo "=>".$strSQL."<br>"; 
				die("#$%");
				return 1;
			}else{
				return $strSQL;
			}				
		}

		$this->objDB->Query($strSQL);	

		$this->LAST_SQL = $strSQL;
		
		if($this->objDB->Get_LastInsert())
			$this->arrRECORD_WORKBENCH["ID"] = $this->objDB->Get_LastInsert();
			
		return $srID;
	}	
	
	public function Create($DATA, $DEBUG=false)
	{
	    $vals = array();
	    $keys = array();
	    
	    $DATA["Created"] = date("Y-m-d H:i:s");

	    unset($DATA["ID"]);
	    
	    foreach ($DATA as $key => $val)
	    {
	        $keys[] = $key;
	        $vals[] = $val;
	    }
	    
	    $keysStr = implode("`, `", $keys);
	    $valsStr = implode("', '", $vals);
	    
	    $sql = "INSERT INTO {$this->DB_TABLE} (`{$keysStr}`) VALUES ('{$valsStr}')";
	    $this->objDB->Query($sql);
	    
	    if($DEBUG)
	        die("=>{$sql}<br>");
	}
	
	public function Clear()
	{
		$this->numINDEX = 0;
		$this->numCOUNT = 0;
		$this->arrRECORDS = array();
		$this->arrRECORD_WORKBENCH = array();
	}	
	
	public function Reset_Fetch()
	{
		$this->numINDEX = 0;
	}
		
	public function Reset()
	{
	    $this->numINDEX = 0;
	    $this->numCOUNT = count($this->arrRECORDS);
	    $this->arrRECORD_WORKBENCH = $this->arrRECORDS[$this->numINDEX];
	}

	public function Dump_Arr($DIE=false, $WORKBENCH=false)
	{
		if($DIE)
			if($WORKBENCH)
				die("<pre>".print_r($this->arrRECORD_WORKBENCH, true)."</pre>");
			else
				die("<pre>".print_r($this->arrRECORDS, true)."</pre>");
			
		
		return $this->arrRECORDS;	
	}
	
	function Dump_Single()
	{
	    return $this->arrRECORD_WORKBENCH;
	}
	
	public function List_Values($KEY='ID', $OUTPUT="string")
	{
	    $buffer = "";
		foreach($this->arrRECORDS as $rec)
		{
			$arrTEMP[] = $rec[$KEY];	
		}		
	
		if($OUTPUT == "array")
		{
		    return $arrTEMP;
		}else{
		    if(is_array($arrTEMP))
		    {
		        return implode(",", $arrTEMP);
		    }
		}
				
		return $buffer;
	}	
	
	public function Find($FIELD, $VALUE)
	{
		foreach ($this->arrRECORDS as $record)
		{
			if ($record[$FIELD] == $VALUE) 
			{
				$this->arrRECORD_WORKBENCH = $record;
				return 1;		
			}
		}
		
		return 0;
	}	

	public function Delete($VAL, $FIELD='ID')
	{
		$strSQL = "DELETE FROM $this->DB_TABLE WHERE $FIELD = '$VAL'";
		$this->objDB->Query($strSQL);		
		return 1;		
	}		
	
	public function Delete_Range($FIELD, $START, $END)
	{
	    $strSQL = "DELETE FROM $this->Table WHERE ";
	    if(!empty($START))
	    {
	        $strSQL .= "{$FIELD} > '{$START}'";
	    }
	    
	    if(!empty($END))
	    {
	        if(!empty($START))
	            $strSQL .= " AND ";
	            
	            $strSQL .= "{$FIELD} < '{$END}'";
	    }
	    
	    $this->objDB->Query($strSQL);
	    return 1;
	}		
	
	function Print_Data($SET= "all", $DIE = FALSE)
	{
	    echo "<pre>";
	    if($SET == 'workbench')
	    {
	        print_r($this->arrRECORD_WORKBENCH);
	    }else{
            print_r($this->arrRECORDS);            
	    }
	    echo "</pre>";
        
        if($DIE) die("@#$%");
	}
	
	public function Number_Records()
	{
		return $this->numCOUNT;		
	}

	public function Last_Executed_SQL()
	{
		return $this->LAST_SQL;
	}	
	
	public function Unset_Value($KEY)
	{
	    unset($this->arrRECORD_WORKBENCH[$KEY]);
	}
	
//  *****************************************************************************************************************
//		SET ATTIBUTES
	public function Set_Value($KEY, $VAL)
	{
	    $this->arrRECORD_WORKBENCH[$KEY] =  htmlentities($VAL);
	    return $this;
	}
	
	public function Set_Value_Raw($KEY, $VAL)
	{
	    $this->arrRECORD_WORKBENCH[$KEY] =  $VAL;
	    return $this;
	}
		
	public function Set_ID($VAL)
	{
		$this->arrRECORD_WORKBENCH["ID"] = $VAL;
		return $this;
	}	
	
	public function Set_SRID($VAL)
	{
		$this->arrRECORD_WORKBENCH["SRID"] = $VAL;
		return $this;
	}		

	public function Set_Modified($VAL)
	{
		$this->arrRECORD_WORKBENCH["Modified"] = $VAL;
	}
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	
	public function Get_Value($KEY)
	{
	    return html_entity_decode($this->arrRECORD_WORKBENCH[$KEY]);
	}
		
	public function Get_ID()
	{
		return $this->arrRECORD_WORKBENCH["ID"];
	}

	public function Get_SRID()
	{
		return $this->arrRECORD_WORKBENCH["SRID"];
	}	

	public function Get_Created()
	{
		return $this->arrRECORD_WORKBENCH["Created"];
	}

	public function Get_Modified()
	{
		return $this->arrRECORD_WORKBENCH["Modified"];
	}
	
//  *****************************************************************************************************************
//	functions not using loaded object

	public function Sort_By($FIELD, $DIRC='up')
	{
		$this->SORT_BY = $FIELD;
		$this->SORT_DIRC = ($DIRC=="down")?"DESC":"ASC";
		
	}	
	
	public function Update_Secure_ID($ID, $SR_ID)
	{
		$sql = "SELECT count(*) AS cnt FROM $this->DB_TABLE WHERE ID = {$ID}";
		$this->objDB->Query($sql);	
		$data = $this->objDB->Fetch_Single();
	
		if($data->cnt > 0)
		{
			$sql = "UPDATE $this->DB_TABLE SET SRID = '{$SR_ID}' WHERE ID = {$ID}";
			$this->objDB->Query($sql);	
		}
	}
}
?>
