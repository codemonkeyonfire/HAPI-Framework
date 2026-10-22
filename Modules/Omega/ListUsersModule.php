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
 
//**************************************************************************************** initialize
namespace Modules\Omega;

use Modules\AbstractModule;

require_once("Modules/AbstractModule.php");
require_once("Handlers/Users/h_User.php");
require_once("Handlers/Users/h_UserRoles.php");
require_once("Handlers/Util/h_Url.php");

class ListUsersModule extends AbstractModule
{

	public function run($PARAMETERS="")
	{
	    if(!isset($objURL))	$objURL = new \Handlers\Util\UrlHandler($this->objRegistry);
				
		$submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
		$USER_ID = $this->objRegistry->Get_Value("USER:ID");
		
		if($USER_ID != 1){die("########");}
		
		$SRID = $submittedData["itm"];
		
		$this->Mode = "";
		
//####################################################	PROCESS REQUEST
			
		if($submittedData["act"] == "addUser")
		{
			$objUSER->Set_Email($submittedData["email"]);
			$objUSER->Set_First_Name($submittedData["firstname"]);
			$objUSER->Set_Last_Name($submittedData["lastname"]);
			$objUSER->Set_User_Name($submittedData["username"]);
				
			$objUSER->Commit();
			
			$userID = $objUSER->Get_ID();
			
			$objUSER->Set_Password($userID, $submittedData["password"]);
		}
		
//####################################################	SETUP FORM		

		$this->arrViewData["userTabs"][] = ["id" => "client", "title" => "Clients", "content" => $this->generate_for_role(3), "styles" => "padding: 5px;"];
		$this->arrViewData["userTabs"][] = ["id" => "vendor", "title" => "Vendors", "content" => $this->generate_for_role(4), "styles" => "padding: 5px;"];
		$this->arrViewData["userTabs"][] = ["id" => "staff", "title" => "Staff", "content" => $this->generate_for_role(2), "styles" => "padding: 5px;"];
		$this->arrViewData["userTabs"][] = ["id" => "admin", "title" => "Administrators", "content" => $this->generate_for_role(1), "styles" => "padding: 5px;"];
		
		$menu = "sidemenu";
		$this->objRegistry->Clear_Menu($menu);
		//Add_Menu_Item($MENU, $ITEM_TEXT, $ITEM_URL, $ITEM_TYPE="link", $ICON="", $AUX_PROPS)
		$this->objRegistry->Add_Menu_Item($menu, "Add User", $objURL->Make("Omega", "AddUser", "", ""), "link", "", ["onclick" => ""]);
		
	}
	
	private function generate_for_role($ROLE_ID)
	{
	    if(!isset($objURL))	$objURL = new \Handlers\Util\UrlHandler($this->objRegistry);
	    if(!isset($objUSER)) $objUSER = new \Handlers\Users\UserHandler($this->objDatabase, $this->objRegistry);
	    if(!isset($objUSER_ROLES)) $objUSER_ROLES = new \Handlers\Users\UserRolesHandler($this->objDatabase, $this->objRegistry);	
	    
	    $DATA = [];
	    
	    $objUSER_ROLES->Load_All("$ROLE_ID");
	    $users = $objUSER_ROLES->List_Values("UserID");
	    
	    if($users != "")
	    {
	        $objUSER->Load_Set("ID", $users);
	    }else{
	        $objUSER->Set_First_Name("no users");
	    }
	    
	    $DATA["table_options"] = ["striped", "bordered", "hover"];
	    
	    ob_flush();
	    $onclick = "document.location.href='".$objURL->Make("", "", "", "")."'";
	    
	     $r = 0;
	    while ($objUSER->Fetch())
	    {
	        $i = 0;
	        $DATA["rows"][$r]["cells"][$i++] = ["data" => $objUSER->Get_First_Name(), "style" => "width: 125px;", "onclick" => $onclick];
	        $DATA["rows"][$r]["cells"][$i++] = ["data" => $objUSER->Get_Last_Name(), "style" => "width: 125px;", "onclick" => $onclick];
	        $DATA["rows"][$r]["cells"][$i++] = ["data" => $objUSER->Get_Email(), "style" => "", "onclick" => $onclick];
	        $r++;
	    }
	    
	    $DATA["controls"][] = ["text" => "ADD", "action" => "document.location.href='".$objURL->Make("Omega", "AddUser", $ROLE_ID, "")."'"];
	    
	    require 'Views/v_List.php';
	    $tabContent = ob_get_clean();
	    ob_start();
	    return $tabContent;
	}
	
	public function display($PARAMETERS="")
	{
		if($this->objRegistry->Get_Value("IS_LIVE:EDIT_CASHFLOW") == "NO")
		{
			echo "This Page is currently unavailable";
			exit();
		}
		
?>
<div class="row">
	<div class="col-12">
		<div class="content_item">
			<div class="row no-gutters">
            	<div class="col-12" style="">
            		<h2>Users</h2>
            	</div>
			</div>
            <div class="row">
                <div class="col-lg-3">
                </div>
            	<div class="col-lg-6">
<?php 	
        $DATA = &$this->arrViewData["userTabs"];
        include 'Views/v_Tabs.php';
?>
				</div>
        		<div class="col-lg-3">
        		</div>
			</div>	
		</div>
	</div>
</div>	
<?php 
	}
}

