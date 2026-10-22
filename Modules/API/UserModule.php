<?php
//**************************************************************************************** initialize
namespace Modules\API;

use Modules\AbstractApiModule;
use Handlers\Users\UserHandler;

class UserModule extends AbstractApiModule
{
    
    function check_email($VARS)
    {
        if(!isset($objUSER)) $objUSER = new UserHandler($this->objDatabase);
    			
    	if($objUSER->Email_Exists($VARS["email"]))
    	{
    		$result = true;
    	}else{
    		$result = false;
    	}
    	
    	return ['action' => 'check_email', 'result' => $result];
    }
    
    function search($VARS)
    {
        if(!isset($objUSER)) $objUSER = new UserHandler($this->objDatabase);
        
        $objUSER->Load_Directory_Search($VARS["q"], explode(",", $VARS["roles"]), $VARS["any"], $VARS["part"]);
        return $objUSER->Fetch_All();

    }
    
    public function display($PARAMETERS="")
    {
        $this->Mode = 'json';
        parent::display($PARAMETERS);
    }

}