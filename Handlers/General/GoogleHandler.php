<?php
/**
 * Template file for creating new handlers files
 * 
 *
 * 
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

require '';

class GoogleHandler
{
	private $Databases;
	private $Registry;
	
	private $Client;
	private $ClientID = "";
	private $ServiceAccountName = "";
	private $DelegatedAdmin = "";
	private $KeyFile = "";
	private $AppName = "i";	
	private $CONFIG;
	
	public function __construct(&$DATABASES, &$REGISTRY)
	{
    	require_once 'libs/google-api-php-client/src/Google/autoload.php';
    	
    	$this->Databases = &$DATABASES;
		$this->Registry = &$REGISTRY;
    	
   		$SCOPES = array("https://www.googleapis.com/auth/admin.directory.user");
    	    	
    	$Creds = new \Google_Auth_AssertionCredentials($this->ServiceAccountName, $SCOPES, file_get_contents($this->KeyFile));
    	
    	$Creds->sub = $this->DelegatedAdmin;
    	
		$this->Client = new \Google_Client();
		$this->Client->setApplicationName($this->AppName);
		$this->Client->setClientId($this->ClientID);
		$this->Client->setAssertionCredentials($Creds);
				
	}			
//#####################################################################################################################	
		
	public function list_accounts()
	{
    	$dir = new \Google_Service_Directory($this->Client);
    	
    	$page = 0;
 		do 
 		{   	
 			$opt_param['maxResults'] = 500;
 			$opt_param['domain'] = "";
 			if ($pageToken) 
		       	$opt_param['pageToken'] = $pageToken;
		    		    
	    	$listResponse = $dir->users->listUsers($opt_param);
	    	
	    	$Users = $listResponse->getUsers();
	    	$pageToken = $listResponse->getNextPageToken();
	    	
	    	foreach ($Users as $user)
	    	{
	    		$extIDs = $user->getExternalIds();
	    		foreach ($extIDs as $eID)
	    			if($eID["type"] == "custom")
	    				$ID[$eID["customType"]] = $eID["value"];
	    			
	    		$email = $user->primaryEmail;
	    		list($username, $domain) = explode("@", $email);
	    		    			
	    		$stat = ($user->getSuspended() == "")?"active":"inactive";
	    		
	    		$Buffer[] = array("status" => $stat, "username" => $username, "acid" => $ID["colleague_id"], "email" => $email,);
	    	}
	    	$page++;
	    } while ($pageToken);		
		
		return $Buffer;
	}
	
	public function set_password($USERNAME, $PASSWORD)
	{
		$registryKey = $this->Registry->Get_Value("GOOGLE-KEY-REGISTRY");
		$authKey = $this->Registry->Get_Value("ENCRYPT-AUTH-KEY");		
		
		$Id = $USERNAME."@inside.artcenter.edu";
		$dir = new \Google_Service_Directory($this->Client);
	    try{   	
    		$UserInfo = $dir->users->get($Id);	
    		$UserInfo->setHashFunction("SHA-1") ;
    		$UserInfo->setPassword(hash("sha1", $PASSWORD));
    		$dir->users->update($Id, $UserInfo);
    		
    		require_once 'handlers/general/handler_utilities.php';
    		$encrypted = UtilitiesHandler::encrypt($PASSWORD, $registryKey, $authKey);	
    		
    		$extIDs = $UserInfo->getExternalIds();
	    	foreach ($extIDs as $eID)
	    		if($eID["type"] == "custom")
	    			$ID[$eID["customType"]] = $eID["value"];

			$sql = "INSERT INTO	gmail_registry 
						(email, colleague_id, status, password, forwarding, created) 
					VALUES 
						('{$Id}', '{$ID["colleague_id"]}', 'active', '{$encrypted}', '', NOW()) 
			        ON DUPLICATE KEY UPDATE 
						password = '{$encrypted}', modified = NOW()";

			$this->Databases["INSIDE"]->Execute($sql);		    		

	    }catch(\Google_Service_Exception $e){
			die($e->getErrors());
		}		
	
		return 1;
	}
	
	public function get_user_info($USERNAME)
	{
		$dir = new \Google_Service_Directory($this->Client);
	    try{   	
    		$UserInfo = $dir->users->get($USERNAME."@inside.artcenter.edu");	  

			$Buffer = array();
				
			$Name = $UserInfo->getName();
				
			$email = $UserInfo->primaryEmail;
		    list($username, $domain) = explode("@", $email);
				
			$Buffer["userName"] = $username;
			$Buffer["firstName"] = $Name->givenName;
			$Buffer["lastName"] = $Name->familyName;
			$Buffer["accountStatus"] = ($UserInfo->getSuspended() == "")?"active":"inactive";
			$Buffer["changePassword"] = ($UserInfo->getChangePasswordAtNextLogin())?"yes":"no";    		
    		
		}catch(\Google_Service_Exception $e){
			echo $e->getErrors();
			$Buffer = "not found";
		}
		
		return $Buffer;
	}
	
	public function create_account($FIRSTNAME="", $LASTNAME="", $PASSWORD="", $USERNAME="", $COLLEAGUE_ID="")
	{
		$registryKey = $this->Registry->Get_Value("GOOGLE-KEY-REGISTRY");
		$authKey = $this->Registry->Get_Value("ENCRYPT-AUTH-KEY");	
		
		$dir = new \Google_Service_Directory($this->Client);
    	
    	$user = new \Google_Service_Directory_User();
		$name = new \Google_Service_Directory_UserName();
		$externalID = new \Google_Service_Directory_UserExternalId();
		
		$name->setGivenName($FIRSTNAME);
		$name->setFamilyName($LASTNAME);
		
		$externalID->setType("custom");
		$externalID->setCustomType("colleague_id");
		$externalID->setValue($COLLEAGUE_ID);
		
		$user->setName($name);
		
		$user->setPrimaryEmail($USERNAME."@inside.artcenter.edu");
		$user->setExternalIds(array($externalID));
		
		$user->setHashFunction("SHA-1");
		
		if(empty($PASSWORD))
		{			
    		require_once 'handlers/general/handler_utilities.php';
    		require_once 'handlers/general/handler_strings.php';
    		
			$PASSWORD = StringsHandler::get_random_string(20, "URL_SAFE");			
			$encrypted = UtilitiesHandler::encrypt($PASSWORD, $registryKey, $authKey);						
		}
		
		$sql = "INSERT INTO gmail_registry (email, colleague_id, status, password, forwarding, created) VALUES ('{$EMAIL}', '{$COLLEAGUE_ID}', 'active', '{$encrypted}', '', NOW())";
		$this->Databases["INSIDE"]->Execute($sql);		
		
		$user->setPassword(hash("sha1", $PASSWORD));
		
		$result = $dir->users->insert($user);
	
		return $result;
	}
	
	public function set_account_status($USERNAME, $STATUS)
	{
		$Id = $USERNAME."@inside.artcenter.edu";
		$dir = new \Google_Service_Directory($this->Client);

		try{   	
			
			if($STATUS == "delete")
			{
				$dir->users->delete($Id);
			}else{
	    		$UserInfo = $dir->users->get($Id);	
	
				if($STATUS == "active")
    				$UserInfo->setSuspended(false) ;
				else
					$UserInfo->setSuspended(true) ;
	     		
	    		$dir->users->update($Id, $UserInfo);
			}

	    }catch(\Google_Service_Exception $e){
			die($e->getErrors());
		}			
		
	}	
	
//#####################################################################################################################	
	
}