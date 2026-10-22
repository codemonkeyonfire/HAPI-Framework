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

class UserHandler extends \Handlers\General\BaseHandler
{
	public function __construct(&$DATABASE, &$SETTINGS="")
	{
		$this->DB_TABLE = "Users";								// set table here, parent will not set it
		
		$this->DB_FIELDS[] = array("name" => "ParentID",		"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "EntityID",		"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "FirstName",		"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "LastName",		"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Username",		"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "profileImage",	"type" => "char|100", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Email",			"type" => "char|100", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Address",			"type" => "char|100", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "City",			"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "State",			"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "CountryID",		"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Zip",				"type" => "char|10", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Role",			"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Phone",			"type" => "char|20", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Fax",				"type" => "char|20", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Enabled",			"type" => "bool", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "LocationID",		"type" => "integer", 	"default" => "0", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Birthday",		"type" => "date", 	"default" => "'0000-00-00'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Title",			"type" => "char|45", 	"default" => "''", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "EmailValidated",	"type" => "datetime", 	"default" => "'0000-00-00 00:00:00'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "SessionToken",	"type" => "char|45", 	"default" => "'0000-00-00 00:00:00'", 	"comment" => "");
		$this->DB_FIELDS[] = array("name" => "Password",	"type" => "char|255", 	"default" => "''", 	"comment" => "");
		
		$this->SORT_BY = "LastName";
		$this->SORT_DIRC = "ASC";		
		parent::__construct($DATABASE, $SETTINGS);
	}	
	
//  *****************************************************************************************************************	
//		CUSTOM LOAD FUNCTIONS

	public function Load_Directory_Search($SEARCH_TEXT, $ROLES=[], $MATCH_ANY=FALSE, $MATCH_PART=FALSE)
	{
	    $bound = ($MATCH_PART)?"%":"";
	    
	    $cond = ($MATCH_ANY)?"OR":"AND";
	    $words = explode(" ", $SEARCH_TEXT);
	    
	    $search_name = "";
	    foreach ($words as $w)
	    {
	        $search_name .= ($search_name!="")?" ".$cond:"";
	        $search_name .= " search_nm LIKE '{$bound}{$w}{$bound}'";
	    }
	    
	    $join = ""; $roleCrit = "";
	    if(!empty($ROLES[0]))
	    {
	        $join = "LEFT JOIN UserRoles r ON r.UserID = u.ID LEFT JOIN RoleDefinitions d ON d.ID = r.roleID";
	        $tmp = implode("','", $ROLES);
	        $roleCrit = " AND d.name IN ('{$tmp}')";	        
	    }
	    
	    $strSQL = "SELECT
					*
				   FROM
				        (SELECT 
                            Users.ID, 
                            Users.SRID, 
                            Users.EntityID, 
                            Users.FirstName, 
                            Users.LastName, 
                            Users.Username, 
                            Users.profileImage, 
                            Users.Email, 
                            Users.Address, 
                            Users.City, 
                            Users.State, 
                            Users.CountryID, 
                            Users.Phone, 
                            Users.Fax, 
                            Users.Enabled, 
                            Users.LocationID, 
                            Users.Birthday, 
                            Users.Title, 
                            LOWER(CONCAT(FirstName, ' ', LastName)) AS search_nm 
                          FROM {$this->DB_TABLE}
                        ) AS u {$join}
				   WHERE
				        (({$search_name}) OR Username = '{$SEARCH_TEXT}' OR Email = '{$SEARCH_TEXT}'){$roleCrit}
	               ORDER BY LastName, FirstName";
   
	    $this->objDB->Query($strSQL);
	    
	    $this->arrRECORDS = $this->objDB->Fetch_Arrays();
	    
	    $this->numINDEX = 0;
	    $this->numCOUNT = $this->objDB->Get_NumRows();
	    
	    return $this->numCOUNT;
	}
	
	
//  *****************************************************************************************************************
//		SET ATTIBUTES

	public function Set_First_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["FirstName"] = $VAL;
	}	
	
	public function Set_Last_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["LastName"] = $VAL;
	}		
	
	public function Set_User_Name($VAL)
	{
		$this->arrRECORD_WORKBENCH["Username"] = $VAL;
	}	

	public function Set_Email($VAL)
	{
		$this->arrRECORD_WORKBENCH["Email"] = $VAL;
	}			
	
	public function Set_Address($VAL)
	{
		$this->arrRECORD_WORKBENCH["Address"] = $VAL;
	}		

	public function Set_City($VAL)
	{
		$this->arrRECORD_WORKBENCH["City"] = $VAL;
	}		

	public function Set_State($VAL)
	{
		$this->arrRECORD_WORKBENCH["State"] = $VAL;
	}		
	
	public function Set_Country_ID($VAL)
	{
		$this->arrRECORD_WORKBENCH["CountryID"] = $VAL;
	}		

	public function Set_Zip($VAL)
	{
		$this->arrRECORD_WORKBENCH["Zip"] = $VAL;
	}		

	public function Set_Role($VAL)
	{
		$this->arrRECORD_WORKBENCH["Role"] = $VAL;
	}		

	public function Set_Phone($VAL)
	{
	    $this->arrRECORD_WORKBENCH["Phone"] = preg_replace('/\D+/', '', $VAL);
	}		

	public function Set_Fax($VAL)
	{
	    $this->arrRECORD_WORKBENCH["Fax"] = preg_replace('/\D+/', '', $VAL);
	}				
	
	public function Set_Status($VAL)
	{
		$this->arrRECORD_WORKBENCH["Enabled"] = $VAL;
	}		

	public function Set_Location($VAL)
	{
		$this->arrRECORD_WORKBENCH["LocationID"] = $VAL;
	}		
	
	public function Set_Birthday($VAL)
	{
		$this->arrRECORD_WORKBENCH["Birthday"] = $VAL;
	}		
	
	public function Set_Title($VAL)
	{
		$this->arrRECORD_WORKBENCH["Title"] = $VAL;
	}		
	
	public function Set_Email_Validated($VAL)
	{
		$this->arrRECORD_WORKBENCH["EmailValidated"] = $VAL;
	}	
		
	public function Set_Token($VAL)
	{
		$this->arrRECORD_WORKBENCH["SessionToken"] = $VAL;
	}		
			
	public function Set_Parent($VAL)
	{
		$this->arrRECORD_WORKBENCH["ParentID"] = $VAL;
	}	

	public function Set_Profile_Image($VAL)
	{
		$this->arrRECORD_WORKBENCH["profileImage"] = $VAL;
	}
		
	public function Set_Company($VAL)
	{
	    $this->arrRECORD_WORKBENCH["EntityID"] = $VAL;
	}
	
//  *****************************************************************************************************************	
//		GET ATTIBUTES	

	public function Get_First_Name()
	{
		return $this->arrRECORD_WORKBENCH["FirstName"];
	}	
	
	public function Get_Last_Name()
	{
		return $this->arrRECORD_WORKBENCH["LastName"];
	}		
	
	public function Get_User_Name()
	{
		return $this->arrRECORD_WORKBENCH["Username"];
	}	
	
	public function Get_Email()
	{
		return $this->arrRECORD_WORKBENCH["Email"];
	}		

	public function Get_Address()
	{
		return $this->arrRECORD_WORKBENCH["Address"];
	}		

	public function Get_City()
	{
		return $this->arrRECORD_WORKBENCH["City"];
	}	
	
	public function Get_State()
	{
		return $this->arrRECORD_WORKBENCH["State"];
	}			
	
	public function Get_Country_ID()
	{
		return $this->arrRECORD_WORKBENCH["CountryID"];
	}	
		
	public function Get_Zip()
	{
		return $this->arrRECORD_WORKBENCH["Zip"];
	}	
	
	public function Get_Role()
	{
		return $this->arrRECORD_WORKBENCH["Role"];
	}				
	
	public function Get_Phone()
	{
	    return $this->arrRECORD_WORKBENCH["Phone"];
	}		

	public function Get_Fax()
	{
		return $this->arrRECORD_WORKBENCH["Fax"];
	}			

	public function Get_Location()
	{
		return $this->arrRECORD_WORKBENCH["LocationID"];
	}		

	public function Get_Status()
	{
		return $this->arrRECORD_WORKBENCH["Enabled"];
	}		

	public function Get_Title()
	{
		return $this->arrRECORD_WORKBENCH["Title"];
	}	
	
	public function Get_Birthday()
	{
		return $this->arrRECORD_WORKBENCH["Birthday"];
	}	
		
	public function Get_First_Last_Name()
	{
		return $this->Get_First_Name()." ".strtoupper(substr($this->Get_Last_Name(), 0, 1));	
	}
	
	public function Get_Email_Validated()
	{
		return $this->arrRECORD_WORKBENCH["EmailValidated"];	
	}	
		
	public function Get_Parent()
	{
		return $this->arrRECORD_WORKBENCH["ParentID"];	
	}		

	public function Get_Porfile_Image()
	{
		return $this->arrRECORD_WORKBENCH["profileImage"];
	}
	
	public function Get_Company()
	{
	    return $this->arrRECORD_WORKBENCH["EntityID"];
	}
	
//  *****************************************************************************************************************

	function Generate_Token($USER_ID=0)
	{
		$uID = ($USER_ID==0)?$this->Get_ID():$USER_ID;
		
		$TOKEN = md5($USER_ID."saltword".time());
		
		$strSQL = "UPDATE $this->DB_TABLE SET SessionToken = '$TOKEN' WHERE ID = $uID";
		$this->objDB->Query($strSQL);	
		
		return $TOKEN;		
	}

	function superHash($source, $storedSalt = null)
	{
	/* When creating a password, use superHash($pass);
	** When checking a password, use <?php if(superHash($pass,$hash)==$hash);?>
	*/		

 	   if ($storedSalt === null)
 	   {
 	       	$salt = substr(md5(uniqid(rand(), true)), 0, 5);
    
 	   }
 	   else
 	   {
   			$salt = $storedSalt[61] . $storedSalt[73] . $storedSalt[14] . $storedSalt[26] . $storedSalt[2];
  		   
    	}
    	
    	$salt1="flyingPigs";
    	$salt2="sadGhosts";

    	$hash1=sha1($salt1.$source.$salt);
    	$hash2=md5($salt2.$source.$salt);
    	$h = $salt.$hash1.$hash2;
    	
    	$scrambledhash = $this->scramble($h);

    	return $scrambledhash;
	}	
	
	function scramble($p) 
	{
    // Assumes that $p is going to be 73 characters long

    	$q = $p[39] . $p[7] . $p[61] . $p[63] . $p[2] . $p[65] . $p[72] . $p[66] . $p[48] . $p[21]
    		. $p[73] . $p[22] . $p[42] . $p[24] . $p[8] . $p[26] . $p[3] . $p[29] . $p[23] . $p[16] 
			. $p[70] . $p[56] . $p[4] . $p[28] . $p[6] . $p[67] . $p[38] . $p[60] . $p[27] . $p[40]
    		. $p[18] . $p[62] . $p[19] . $p[32] . $p[33] . $p[5] . $p[34] . $p[35] . $p[36] . $p[37] 
    		. $p[45] . $p[68] . $p[47] . $p[64] . $p[49]. $p[30] . $p[31] . $p[13] . $p[71] . $p[50] 
    		. $p[10] . $p[0] . $p[51] . $p[11] . $p[9] . $p[8] . $p[7]  . $p[6] . $p[6] . $p[5]
    		. $p[52] . $p[69] . $p[53] . $p[44] . $p[57] . $p[46] . $p[59] . $p[9] . $p[54] . $p[25]			
    		. $p[4]. $p[3] . $p[2] . $p[1];
        

        return $q;
	}	

	public function Set_Password($UID, $PASSWD)
	{
	    $PEPPER = $this->objRegistry->Get_Value("ENCRYPT:HASH_PEPPER");
	    $pwd_peppered = hash_hmac("sha256", $PASSWD, $PEPPER);
		$pwd_hashed = password_hash($pwd_peppered, PASSWORD_ARGON2I);
	
		$strSQL = "UPDATE $this->DB_TABLE SET Password = '$pwd_hashed' WHERE ID = $UID";
		
		$this->objDB->Query($strSQL);		
	}		
	

	//****************************************************************************************
	//	login function
	//
	//	Generates session token if supplied username and password exist in system
	//	and saves a copy in broswer cookie and in users table
	//  TODO - add recording of ip address on login
	//****************************************************************************************	
	public function Authenticate($UNAME, $PASSWD)
	{
		$UNAME = $this->objDB->Escape_Special($UNAME);
		$strSQL = "SELECT ID, Password FROM $this->DB_TABLE WHERE Username = '{$UNAME}' OR Email = '{$UNAME}'";

		$this->objDB->Query($strSQL);

		$arrRECORD = $this->objDB->Fetch_Arrays();
		
		if($this->objDB->Get_NumRows())
		{
			$PASS_HASH = $arrRECORD[0]["Password"];

			$PEPPER = $this->objRegistry->Get_Value("ENCRYPT:HASH_PEPPER");
			$pwd_peppered = hash_hmac("sha256", $PASSWD, $PEPPER);               
			if(password_verify($pwd_peppered, $PASS_HASH))
			{
			    $this->Load($arrRECORD[0]["ID"]);
			    return 1;
			}
		}
		
		return 0;
	}
	
	public function Email_Exists($EMAIL)
	{
		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE Email = '$EMAIL'";

		$this->objDB->Query($strSQL);	

		$arrRECORD = $this->objDB->Fetch_Arrays();

		if ($this->objDB->Get_NumRows() > 0)
		{
			return true;
		}		
		
		return false;		
	}

	public function Username_Exists($USERNAME)
	{
		$strSQL = "SELECT * FROM $this->DB_TABLE WHERE Username = '$USERNAME'";
		$this->objDB->Query($strSQL);
	
		$arrRECORD = $this->objDB->Fetch_Arrays();
	
		if ($this->objDB->Get_NumRows() > 0)
		{
			return true;
		}
	
		return false;
	}
}

?>
