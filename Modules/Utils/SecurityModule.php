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
namespace Modules\Utils;

use Modules\AbstractModule;
 
class SecurityModule extends AbstractModule 
{
	private $Default_Module;
	private $User_Role;

	public function run($PARAMETERS="")
	{

	    if(!isset($objUser)) $objUser = new \Handlers\Users\UserHandler($this->objDatabase);
	    
	    if(!isset($objUserSession)) $objUserSession = new \Handlers\Users\UserSessionHandler($this->objDatabase);
	    
	    if(!isset($objUserRoles)) $objUserRoles = new \Handlers\Users\UserRolesHandler($this->objDatabase, $this->objRegistry);	
	    
	    if(!isset($objRoleDefs)) $objRoleDefs = new \Handlers\Users\RoleDefsHandler($this->objDatabase, $this->objRegistry);	
	    
	    if(!isset($objApps)) $objApps = new \Handlers\General\AppsHandler($this->objDatabase);
	    
	    if(!isset($objAppModules)) $objAppModules = new \Handlers\General\AppModulesHandler($this->objDatabase);
	    	    
	    $objAppModules->Set_App($this->objRegistry->Get_Value("SITE:APP_NAME"));
	    $objAppModules->Set_Name($this->objRegistry->Get_Value("SITE:MAIN_MODULE"));
	    $objAppModules->Search();
	    
	    $viewRoles = [];
	    $viewRoles = $objAppModules->Get_Roles_View();
	    
	    if(empty($viewRoles[0]))
	    {
	        $objApps->Set_Name($this->objRegistry->Get_Value("SITE:APP_NAME"));
	        $objApps->Search();
	        
	        $viewRoles = $objApps->Get_Roles_View();
	    }
	    
	    $role_guest = $objRoleDefs->Get_ID("guest");

		$UAID = $this->objRegistry->Get_Value("SESSION:UAID");
		
		if(empty($UAID))
		{ 
		    if(in_array($role_guest, $viewRoles))
		    {  
		        // no need to log-in. guest role must be specified in view roles
		        $this->objRegistry->Set_Value("USER:USERNAME", "guest");
		        $this->objRegistry->Set_Value("USER:ROLES", [$role_guest]);
		    }else{
                $this->objRegistry->Set_Value("SITE:APP_NAME", "User");
                $this->objRegistry->Set_Value("SITE:MAIN_MODULE", "Login");
		    }
		    return 1;
		}else{
		    $user_id = $objUserSession->is_Logged_In($UAID);
		}
		
		if($user_id > 0)
		{
			$this->objRegistry->Set_Value("SESSION:tCoord", $objUserSession->Get_Tan_Coord());
						
			$objUser->Load($user_id);
			
			$objUserRoles->Load_Active($user_id);
			
			$roles = [];
			while($objUserRoles->Fetch())
			{
			    $roles[] = $objUserRoles->Get_ID();
			}
			
			$roles[] = 6;
			
			$this->objRegistry->Set_Value("USER:ID", $user_id);
			$this->objRegistry->Set_Value("USER:ROLES", $roles);
			$this->objRegistry->Set_Value("USER:NAME", $objUser->Get_First_Name());
			$this->objRegistry->Set_Value("USER:USERNAME", $objUser->Get_User_Name());
			
			$validated_date = $objUser->Get_Email_Validated();
			
			if($validated_date == "0000-00-00 00:00:00")
			{
			    $this->objRegistry->Set_Value("SITE:APP_NAME", "User");
			    $this->objRegistry->Set_Value("SITE:MAIN_MODULE", "ValidateEmail");
			    return 1;
			}		
		    			
			if(!empty($viewRoles[0]) && !$objUserRoles->has_one_role($viewRoles))
			{
			    $this->objRegistry->Set_Value("SITE:APP_NAME", "Notice");
			    $this->objRegistry->Set_Value("SITE:MAIN_MODULE", "Error");
			    $this->objRegistry->Set_Value("SITE:MODULE_ITEM", "403");
			    return 1;
			}
			
			// view role not specified allows all logged in users
		}else{
		    $this->objRegistry->Set_Value("SITE:APP_NAME", "User");
		    $this->objRegistry->Set_Value("SITE:MAIN_MODULE", "Login");
		}

	}
	
	public function display($PARAMETERS="")
	{
	
	}
	
	public function get_default_module()
	{
		return $this->Default_Module;
	}
	
	public function get_user_role()
	{
		return $this->User_Role;
	}
	
	public function get_username()
	{
	    return $this->objRegistry->Get_Value("USER:USERNAME");
	}
	
}

?>