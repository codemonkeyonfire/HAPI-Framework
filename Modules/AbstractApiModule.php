<?php

class AbstractApiModule
{
    protected string $requestMethod;
	protected $objDatabase;
	protected $objRegistry;
	protected $arrData;
	protected $Mode;
	protected $MiscJS;
	public $template;
	
	public function run($PARAMETERS="")
	{        
	   $this->requestMethod = $_SERVER['REQUEST_METHOD'];
       $Func = $PARAMETERS["itm"];
       $this->arrData["payload"] = $this->$Func($PARAMETERS);
	}
	
	public function display($PARAMETERS="")
	{
	    switch ($this->Mode)
	    {
	        case "json":
	            header("Content-Type: text/javascript; charset=utf");
	            header("Access-Control-Allow-Origin: *");
	            //header("Access-Control-Allow-Origin: http://{$config["server"]["host"]}");
	            header("Access-Control-Max-Age: 3628800");
	            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
	            
	            $offset = 3600 * 24;
	            header("Cache-Control: max-age=$offset, must-revalidate");
	            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT');
	            echo json_encode($this->arrData["payload"]);
	            break;
	        case "csv":
	            $this->_checkoutput();
	            header("Pragma: public");
	            header("Expires: 0");
	            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	            header("Cache-Control: private", false);
	            header("Content-Type: application/octet-stream");
	            header("Content-Disposition: attachment; filename={$this->arrData["filename"]}.csv");
	            header("Content-Transfer-Encoding: binary");
	            
	            $csvHeaders = implode('","', $this->arrData["headers"]);
	            echo "\"{$csvHeaders}\"\r\n";
	            
	            $numCols = count($this->arrData["rows"][0]);
	            foreach ($this->arrData["rows"] as $row)
	            {
	                $buffer = "";
	                for ($c = 0; $c < $numCols; $c++)
	                {
	                    if(!empty($buffer)) $buffer .= "\",\"";
	                    $buffer .= $row[$c];
	                }
	                
	                echo "\"{$buffer}\"\r\n";
	                
	            }
	            break;
	        case "pdf" :
	            if(PHP_SAPI!='cli')
	            {
	                // We send to a browser
	                header('Content-Type: application/pdf');
	                header('Content-Disposition: inline; '.$this->_httpencode('filename',$this->arrData["filename"],false));
	                header('Cache-Control: private, max-age=0, must-revalidate');
	                header('Pragma: public');
	            }
	            echo $this->arrData["payload"];
	            break;
	        case "raw":
	            echo $this->arrData["payload"];
	            break;
	        default:
	            // does nothing, allows implementing class make unique output
	    }
	}
	
	public function __construct(&$REGISTRY="", &$DATABASE="")
	{
		$this->objDatabase = &$DATABASE;
		$this->objRegistry = &$REGISTRY;
		
		$this->template = "oneColumnWithSideMenu";
	}
	
	protected function get_view()
	{
		
		
	}
	
	protected function _checkoutput()
	{
	    if(PHP_SAPI!='cli')
	    {
	        if(headers_sent($file,$line))
	            die("Some data has already been output, can't send PDF file (output started at $file:$line)");
	    }
	    if(ob_get_length())
	    {
	        // The output buffer is not empty
	        if(preg_match('/^(\xEF\xBB\xBF)?\s*$/',ob_get_contents()))
	        {
	            // It contains only a UTF-8 BOM and/or whitespace, let's clean it
	            ob_clean();
	        }
	        else
	            die("Some data has already been output, can't send PDF file");
	    }
	}
	
	protected function _isascii($s)
	{
	    // Test if string is ASCII
	    $nb = strlen($s);
	    for($i=0;$i<$nb;$i++)
	    {
	        if(ord($s[$i])>127)
	            return false;
	    }
	    return true;
	}
	
	protected function _httpencode($param, $value, $isUTF8)
	{
	    // Encode HTTP header field parameter
	    if($this->_isascii($value))
	        return $param.'="'.$value.'"';
	        if(!$isUTF8)
	            $value = utf8_encode($value);
	            if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false)
	                return $param.'="'.rawurlencode($value).'"';
	                else
	                    return $param."*=UTF-8''".rawurlencode($value);
	}
	
	protected function dump_array($ARRAY, $DIE=false)
	{
		if($DIE)
			die("<pre>".print_r($ARRAY, true)."</pre>");
		
		echo "<pre>".print_r($ARRAY, true)."</pre>";
	}
		

	
}
?>