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
namespace Handlers\Users;
use Handlers\General\BaseHandler;
 
class UserSessionHandler extends BaseHandler
{
	public function __construct(&$DATABASE, &$SETTINGS="")
	{
		$this->DB_TABLE = "UserSessions";								// set table here, parent will not set it
		
		$this->DB_FIELDS[] = array("name" => "UserID",			"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Token",			"type" => "char|65", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "LastLogin",		"type" => "datetime", 	"default" => "'0000-00-00 00:00:00'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "LastLogout",		"type" => "datetime", 	"default" => "'0000-00-00 00:00:00'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "IPaddress",		"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Tan",				"type" => "char|128", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "TanStart",		"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "TanEnd",			"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "FailedAttempts",	"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "LastAttempt",		"type" => "datetime", 	"default" => "'0000-00-00 00:00:00'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "FailedPasswds",	"type" => "char|100", 	"default" => "''", 	"comment" => "");
		
		parent::__construct($DATABASE, $SETTINGS);
	}
	
//  *****************************************************************************************************************	
//		CUSTOM LOAD FUNCTIONS

//  *****************************************************************************************************************
//		SET ATTIBUTES

	public function Set_User($VAL)
	{
		$this->arrRECORD_WORKBENCH["UserID"] = $VAL;
	}	

	public function Set_Token($VAL)
	{
		$this->arrRECORD_WORKBENCH["Token"] = $VAL;
	}	

	public function Set_Last_Login($VAL)
	{
		$this->arrRECORD_WORKBENCH["LastLogin"] = $VAL;
	}	

	public function Set_Last_Logout($VAL)
	{
		$this->arrRECORD_WORKBENCH["LastLogout"] = $VAL;
	}	

	public function Set_IP_Address($VAL)
	{
		$this->arrRECORD_WORKBENCH["IPaddress"] = $VAL;
	}	

	public function Set_Tan_Start($VAL)
	{
		$this->arrRECORD_WORKBENCH["TanStart"] = $VAL;
	}	

	public function Set_Tan_End($VAL)
	{
		$this->arrRECORD_WORKBENCH["TanEnd"] = $VAL;
	}	
				
//  *****************************************************************************************************************	
//		GET ATTIBUTES	
	public function Get_User()
	{
		return $this->arrRECORD_WORKBENCH["UserID"];
	}	

	public function Get_Token()
	{
		return $this->arrRECORD_WORKBENCH["Token"];
	}	

	public function Get_Last_Login()
	{
		return $this->arrRECORD_WORKBENCH["LastLogin"];
	}	

	public function Get_Last_Logout()
	{
		return $this->arrRECORD_WORKBENCH["LastLogout"];
	}	

	public function Get_IP_Address()
	{
		return $this->arrRECORD_WORKBENCH["IPaddress"];
	}

	public function Get_Tan_String()
	{
		return 	$this->arrRECORD_WORKBENCH["Tan"];
	}	
	
	public function Get_Tan_Coord()
	{
		return 	$this->arrRECORD_WORKBENCH["TanStart"]."-".$this->arrRECORD_WORKBENCH["TanEnd"];
	}
//  *****************************************************************************************************************
//	functions not using loaded object

	public function Create_Session($USER_ID, $TOKEN)
	{
		$ip = $this->IP_Address();
		
		$tan = $this->Generate_Tan($USER_ID);

		$start = rand(0, 120);
		$end = $start + 8;
	    		
		$strSQL = "INSERT INTO $this->DB_TABLE 
					(UserID, Token, LastLogin, IPaddress, Tan, TanStart, TanEnd, Created) 
						VALUES 
					($USER_ID, '$TOKEN', NOW(), '$ip', '$tan', $start, $end, NOW()) 
					ON DUPLICATE KEY UPDATE Token = '$TOKEN', LastLogin = NOW(), IPaddress = '$ip', Tan = '$tan', TanStart = $start, TanEnd = $end, FailedAttempts = 0";

		$this->objDB->Query($strSQL);	
		
		$this->Set_Tan_Start($start);
		$this->Set_Tan_End($end);

		return $tan;	
	}
	
	public function Destroy($USER_ID)
	{
		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE Email = '$EMAIL'";

		$this->objDB->Query($strSQL);			
	}
	
	public function is_Logged_In_Tan($TOKEN, $TAN_CODE)
	{
		$ip = $this->IP_Address();
		
		$this->Set_Token($this->objDB->Escape_Special($TOKEN));
		$found = $this->Search();		
	
		$this->Fetch();

		if ($found > 0)
		{
			list($t_start, $t_end) = explode("-", $this->Get_Tan_Coord());
			$authentic_tan_code = substr($this->Get_Tan_String(), intval($t_start), intval($t_end) - intval($t_start));
			if($TAN_CODE == $authentic_tan_code)
			{
				$UserID = $this->Get_User();
				$this->New_Tan_Coords();
				return $UserID;
			}else{ 
				echo "tan code failed<br>";
				return 0;
			}
		}else{
			return 0;
		}		
	}
	
	public function is_Logged_In($TOKEN)
	{
	    $ip = $this->IP_Address();
	    
	    $this->Set_Token($this->objDB->Escape_Special($TOKEN));
	    $found = $this->Search();
	    
	    $this->Fetch();
	    
	    if ($found > 0)
	    {
            $UserID = $this->Get_User();
            return $UserID;
	    }else{
	        return 0;
	    }
	}
	
	public function Generate_Tan($SALT)
	{
		return md5(date("u")).md5(date("YmdHis")).md5($_SERVER['HTTP_CLIENT_IP']).md5($SALT);
	}	
	
	public function New_Tan_Coords()
	{
		$start = rand(0, 120);
		$this->Set_Tan_Start($start);
		$end = $start + 8;
		$this->Set_Tan_End($end);
		$this->Commit();		
	}
	
	public function IP_Address()
	{
	    if (!empty($_SERVER['HTTP_CLIENT_IP']))  
	    	$ip=$_SERVER['HTTP_CLIENT_IP'];
	    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
	        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	    else
	        $ip=$_SERVER['REMOTE_ADDR'];
	        
	    return $ip;    	
	}
}

?>