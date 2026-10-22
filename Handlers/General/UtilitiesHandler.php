<?php
/**
 * Database Handler
 * can only implement drivers
 * can only use other handlers if they are passed by reference
 * Do not reference Globals except for lib paths (explicitly pass values to functions)
 * can implement base class if and only if base class used by more than one handlers
*
* @author 
* @version 1.0
* @package insideArtCenter
* @subpackage common
* @date 6/14/12
*/
namespace Handlers\General;
use Exception;

class UtilitiesHandler 
{
	protected $Registry;
	const METHOD = 'aes-256-cbc';
	
	function __construct(&$REGISTRY)
	{
		$this->Registry = &$REGISTRY;
	}
		
	public function Curl_Request($URL, $PARAMETER_TYPE="get-string", $PARAMETERS="", $RESULT_TYPE="")
	{
		if(!empty($PARAMETERS))
		{
			switch($PARAMETER_TYPE)
			{
				case "post-xml":
					$queryString = $PARAMETERS;
					break;
				case "post-json":
					$queryString = json_encode($PARAMETERS);
					break;	
				case "post-string":
			        foreach ($PARAMETERS as $key => $value) 
			        {
			            $options[] = $key . "=" . $value;
			        }
			        $queryString = join("&",$options);			        
					break;	
				case "get-string":
			        foreach ($PARAMETERS as $key => $value) 
			        {
			            $options[] = $key . "=" .urlencode($value);
			        }
			        
			        $queryString = join("&",$options);	

			        if(strpos($URL, "?") === false)
			        	$URL .= "?";
			        else 
			        	$URL .= "&";
			        
			        $URL .= $queryString;	
			        break;
				default:
			}

		}

        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $URL);
        curl_setopt ($curl, CURLOPT_USERAGENT, sprintf("Mozilla/%d.0",rand(4,5)));
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        if($PARAMETER_TYPE == "post-xml")
        	curl_setopt ($curl, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        if($PARAMETER_TYPE == "post-json")
        	curl_setopt ($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/json", "Content-Length: ".strlen($queryString)));
        
        if(substr($PARAMETER_TYPE, 0, 5) == "post-")
        {
        	curl_setopt($curl,CURLOPT_POST, true);
        	curl_setopt ($curl, CURLOPT_POSTFIELDS, trim($queryString));
        }
        
        curl_setopt ($curl, CURLOPT_PORT,443);
        
        $buffer = curl_exec($curl);
        $info = curl_getinfo($curl);

        if(!empty($RESULT_TYPE))
        {
        	switch ($RESULT_TYPE)
        	{
        		case "html":
        			$result = $this->Xml_To_Array($buffer);
        			break;
        		case "xml":
        			@$result = simplexml_load_string($buffer);
        			break;
        		case "json":
        			$result = json_decode($buffer);
        			break;
        		case "text":
        			$result = $buffer;
        			break;        			        			        			
        	}
        }else{     
	        switch($info["content_type"])
	        {
	        	case "text/html":
	        		$result = $this->Xml_To_Array($buffer);
	        		break;
	        	case "text/xml":
	        		@$result = simplexml_load_string($buffer);
	        		break;
	        	case "application/json":
	        		$result = json_decode($buffer);
	        		break;
	        	default:
	        		$result = $buffer;
	        }
        }

        return $result;
	} 
	
    public function Xml_To_Array($XML_STR) 
    {
        $xml = new \DOMDocument();
        $xml->preserveWhiteSpace = false;
        
        try 
        {
            $xml->loadXML($XML_STR);
            return $this->r_Xml_To_Array($xml);
        } catch (\Exception $e) {
            return array();
        }
    }	
	
    private function r_Xml_To_Array($XML_DOC) 
    {
        $result = array();
        
        foreach($XML_DOC->childNodes as $nc)
            ($nc->hasChildNodes())
                    ?($XML_DOC->firstChild->nodeName == $XML_DOC->lastChild->nodeName && $XML_DOC->childNodes->length > 1)
                    ?$return[$nc->nodeName][] = $this->r_Xml_To_Array($item)
                    :$return[$nc->nodeName] = $this->r_Xml_To_Array($nc)
                    :$return= $nc->nodeValue;
        return $return;
    }	
    
	/**
	 * Returns an encrypted & utf8-encoded
	 */
	public static function encrypt($TEXT, $CYPHER_PHRASE, $AUTH_PHRASE) 
	{
		$CYPHER_KEY = pack('H*', hash("SHA256", $CYPHER_PHRASE));
		$AUTH_KEY = pack('H*', hash("SHA256", $AUTH_PHRASE));
		
		if(mb_strlen($CYPHER_KEY, '8bit') !== 32)
		{
			throw new Exception("Needs a 256-bit key!");
		}
		
		$iv_size = openssl_cipher_iv_length(self::METHOD);
		$iv = openssl_random_pseudo_bytes($iv_size);
		
		$cypher = openssl_encrypt($TEXT, self::METHOD, $CYPHER_KEY, 0, $iv);
		
		$hmac = hash_hmac("sha256", $iv.$cypher, $AUTH_KEY, true);
	    
	    return $hmac.":".$iv.":".$cypher;
	}
	
	/**
	 * Returns decrypted original string
	 */
	public static function decrypt($CYPHERTEXT, $CYPHER_PHRASE, $AUTH_PHRASE) 
	{
		$CYPHER_KEY = pack('H*', hash("SHA256", $CYPHER_PHRASE));
		$AUTH_KEY = pack('H*', hash("SHA256", $AUTH_PHRASE));
		
		if(mb_strlen($CYPHER_KEY, '8bit') !== 32)
		{
			throw new Exception("Needs a 256-bit key!");
		}
		
		$parts = explode(":", $CYPHERTEXT);
		$iv = $parts[1];
		$cypher = $parts[2];
		$hmac = $parts[0];
		
		$calculated = hash_hmac("sha256", $iv.$cypher, $AUTH_KEY, true);
		
		if(self::hash_equals($hmac, $calculated))
		{
			$text = openssl_decrypt($cypher, self::METHOD, $CYPHER_KEY, 0, $iv);
		}else{
			$text = "FAILED AUTHENTICATION";
		}
		
	    return $text;
	}  
	
	public static function create_key()
	{
		return openssl_random_pseudo_bytes(32);
	}
	
	public static function hash_equals($STRING1, $STRING2)
	{
		return substr_count($STRING1 ^ $STRING2, "\0") * 2 === strlen($STRING1 . $STRING2);
	}

}