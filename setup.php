<?php
	ini_set("display_errors", "on");
	ini_set("error_reporting", E_ALL & ~E_NOTICE);
			
	$handlers_folder = "Handlers";
	
	if($_REQUEST["createdb"])
	{
	    require_once("Drivers/MysqliDriver.php");
	    require_once("Drivers/FileDriver.php");
	    
	    $db = new MysqliDriver();
	    
	    $db->connect("localhost", "cashcow_usr", "8e8id1V3LunaMIXI5ey6m34oFi3OM2", "cashcow");
	    
	    list($handlers_group, $handler_object) = explode("-", $_REQUEST["createdb"]);
	    
	    if($handler_object != "all")
	    {
	        $handlers[] = $handler_object."Handler.php";
	    }else{
	        $handlers = FileDriver::List_Files($handlers_folder."/".$handlers_group, "php");
	    }
	    
	    foreach ($handlers as $handler_file)
	    {
	        if(!is_readable($_SERVER["DOCUMENT_ROOT"]."/cashCow/{$handlers_folder}/{$handlers_group}/{$handler_file}")) echo "file does not exist<br>";
	        $f = fopen($_SERVER["DOCUMENT_ROOT"]."/cashCow/{$handlers_folder}/{$handlers_group}/{$handler_file}", 'r');
	        
	        $class = "";
	        while($line = fgets($f))
	        {
	            $items = explode(" ", $line);
	            //echo "<pre>";print_r($items);	echo "</pre>";
	            $cnt = count($items);
	            for($i = 0; $i < $cnt; $i++)
	            {
	                if($items[$i] == "class")
	                {
	                    $class = $items[$i+1];
	                    break(2);
	                }
	            }
	        }
	        
	        fclose($f);
	        echo "=>$handler_file - $class - {$handlers_folder}/{$handler_file}<br>";
	        
	        if(!empty($class))
	        {
	            require_once("{$handlers_folder}/{$handlers_group}/{$handler_file}");
	            
	            $tempObj = new $class($db, $temp);
	            
	            if(!empty($tempObj->DB_FIELDS))
	            {
	                $db->Query("DROP TABLE IF EXISTS {$tempObj->DB_TABLE}");
	                
	                $sql = "CREATE TABLE {$tempObj->DB_TABLE} (";
	                $sql .= "ID int(10) unsigned NOT NULL AUTO_INCREMENT, ";
	                $sql .= "SRID char(10) NOT NULL DEFAULT '', ";
	                
	                foreach($tempObj->DB_FIELDS as $fld)
	                {
	                    list($typ, $len) = explode("|", $fld["type"]);
	                    
	                    switch ($typ)
	                    {
	                        case "integer":
	                            $typ = "int"; $len = "10"; $opts = "unsigned NOT NULL";
	                            break;
	                        case "date":
	                        case "datetime":
	                            $len = "0";
	                        case "char":
	                        case "varchar":
	                            $opts = "NOT NULL";
	                            break;
	                        case "enum":
	                            $temp = str_replace(",", "','", $len);
	                            $len = "'$temp'"; $opts = "NOT NULL";
	                            break;
	                        case "bool":
	                            $typ = "tinyint"; $len = "1"; $opts = "NOT NULL";
	                            break;
	                        case "money":
	                            $typ = "decimal"; $len = "12,2"; $opts = "NOT NULL";
	                            break;
	                        case "text":
	                            $len = ""; $opts = "";
	                            break;
	                        default:
	                            
	                    }
	                    
	                    $column_type =
	                    
	                    $sql .= "{$fld["name"]} ";
	                    $sql .= ($len)?"$typ($len) $opts ":"$typ $opts ";
	                    $sql .= (!empty($fld["default"]))?"DEFAULT {$fld["default"]} ":"";
	                    $sql .= (!empty($fld["default"]))?"COMMENT '{$fld["comment"]}'":"";
	                    $sql .= ", ";
	                }
	                
	                $sql .= "created datetime NOT NULL DEFAULT '0000-00-00 00:00:00', ";
	                $sql .= "PRIMARY KEY (ID) ";
	                $sql .= ")";
	                
	                echo "<div style=\"font-size: 10px;\">$sql</div>";
	                
	                $db->Query($sql);
	            }
	        }
	    }
	}
	
echo "DONE";
?>