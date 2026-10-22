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

define("NULL_ENTITY_ID", "0000000000");

class AddUserModule extends AbstractModule
{

	public function run($PARAMETERS="")
	{
	    if(!isset($objUSER)) $objUSER = new \Handlers\Users\UserHandler($this->objDatabase, $this->objRegistry);
	    if(!isset($objUSER_ROLES)) $objUSER_ROLES = new \Handlers\Users\UserRolesHandler($this->objDatabase, $this->objRegistry);
	    if(!isset($objUSER_ROLES_DEFS)) $objUSER_ROLES_DEFS = new \Handlers\Users\RoleDefsHandler($this->objDatabase, $this->objRegistry);
		if(!isset($objENTITIES)) $objENTITIES = new \Handlers\Financial\EntityHandler($this->objDatabase);	
						
		$submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
		$USER_ID = $this->objRegistry->Get_Value("USER:ID");
		
		if($USER_ID != 1){die("########");}
		
		$USER_TYPE = $submittedData["itm"];
		
		$this->Mode = "";
		
//####################################################	PROCESS REQUEST
			
		if($submittedData["act"] == "addUser")
		{
			$objUSER->Set_Email($submittedData["email"]);
			$objUSER->Set_First_Name($submittedData["firstname"]);
			$objUSER->Set_Last_Name($submittedData["lastname"]);
			$objUSER->Set_User_Name($submittedData["username"]);
			
			$objUSER->Set_Email($submittedData["email"]);
			$objUSER->Set_Fax($submittedData["fax"]);
			$objUSER->Set_Phone($submittedData["phone"]);
			
			$objUSER->Set_Address($submittedData["address"]);
			$objUSER->Set_City($submittedData["city"]);
			$objUSER->Set_State($submittedData["state"]);
			$objUSER->Set_Zip($submittedData["zip"]);			
			
			$objUSER->Set_Title($submittedData["title"]);
			
			if($submittedData["entity"] == NULL_ENTITY_ID)
			{
			    $objENTITIES->Set_Type($objUSER_ROLES_DEFS->Get_Name($submittedData["userType"]));
			    $objENTITIES->Set_User($USER_ID);
			    $objENTITIES->Set_Name($submittedData["cname"]);
			    $objENTITIES->Set_Email($submittedData["cemail"]);
			    $objENTITIES->Set_Address($submittedData["caddress"]);
			    $objENTITIES->Set_City($submittedData["ccity"]);
			    $objENTITIES->Set_State($submittedData["cstate"]);
			    $objENTITIES->Set_Zip($submittedData["czip"]);
			    $objENTITIES->Set_Phone($submittedData["cphone"]);
			    $objENTITIES->Set_Fax($submittedData["cfax"]);
			    
			    $objENTITIES->Commit();
			    $submittedData["entity"] = $objENTITIES->Get_ID();
			}
					
			$objUSER->Set_Company($submittedData["entity"]);
			
			$objUSER->Commit();
			
			$userID = $objUSER->Get_ID();
			
			$objUSER->Set_Password($userID, $submittedData["password"]);
			
			$objUSER_ROLES->Set_User($userID);
			$objUSER_ROLES->Set_RoleID($submittedData["userType"]);
			
			$objUSER_ROLES->Commit();
		}
		
//####################################################	SETUP FORM		
		if(!isset($objURL))	$objURL = new \Handlers\Util\UrlHandler($this->objRegistry);		
		
		
		require_once("Handlers/Forms/FormHandler.php");
		if(!isset($objFORM)) $objFORM = new \Handlers\Forms\FormHandler($this->objRegistry, "add_user", $objURL->Make("Omega", "AddUser", "", ""));
		
		$objFORM->Set_Submit_Text("Add");
		$objFORM->Add_Hidden_Value("act", "addUser");
		
		if(!empty($USER_TYPE))
		{
		    $set["occupy"] = "whole";
		    $objFORM->Add_label("User Type", $objUSER_ROLES_DEFS->Get_Name($USER_TYPE), "", $set);
		    $objFORM->Add_Hidden_Value("userType", $USER_TYPE);
		}else{
		    $objUSER_ROLES_DEFS->Set_Type("userPrimary");
		    $objUSER_ROLES_DEFS->Set_Status("enabled");
		    $objUSER_ROLES_DEFS->Search();
		    
		    while($objUSER_ROLES_DEFS->Fetch())
		    {
		        $opts[] = ["text" => $objUSER_ROLES_DEFS->Get_Name(), "value" => $objUSER_ROLES_DEFS->Get_ID()];
		    }
		    
		    $set["occupy"] = "whole";
		    $objFORM->Add_radiobutton("User Type", "userType", "", "3", "", true, $opts, $set);
		}
		
		$set["occupy"] = "6";
		
		$objFORM->Add_textbox("First Name", "firstname", "", "", "", "", "", $set);
		$objFORM->Add_textbox("Last Name", "lastname", "", "", "", "", "", $set);
		
		$objFORM->Add_space( "", ["occupy" => "whole"]);
		
		$objFORM->Add_textbox("Username", "username", "", "", "", "", "", $set);
		$objFORM->Add_textbox("Password", "password", "", "", "", "", "", $set);
		
		$objFORM->Add_space( "", ["occupy" => "whole"]);
		
		$set["occupy"] = "whole";
		$objFORM->Add_textbox("Email", "email", "", "", "", "", "", $set);
				
		$set["occupy"] = "6";
		$objFORM->Add_textbox("Phone Number", "phone", "", "", "", "", "", $set);
		$objFORM->Add_textbox("Fax Number", "fax", "", "", "", "", "", $set);
		
		$objFORM->Add_space( "", ["occupy" => "whole"]);
		
		$set["occupy"] = "whole";
		$objFORM->Add_textarea("Address", "address", "", "", "", "", "", $set);
	
		$set["occupy"] = "4";
		$objFORM->Add_textbox("City", "city", "", "", "", "", "", $set);
		$objFORM->Add_textbox("State", "state", "", "", "", "", "", $set);
		$objFORM->Add_textbox("Zip", "zip", "", "", "", "", "", $set);

		$objFORM->Add_space( "", ["occupy" => "whole"]);
		
		$set["occupy"] = "whole";
		$objFORM->Add_textbox("Title", "title", "", "", "", "", "", $set);
		
		$objENTITIES->Clear();
		$objENTITIES->Sort_By("Name");
		$objENTITIES->Load_All();
		
		$clients[] = ["text" => "-- New Company --", "value" => NULL_ENTITY_ID];
		
		while($objENTITIES->Fetch())
		{
		    $clients[] = ["text" => $objENTITIES->Get_Name(), "value" => $objENTITIES->Get_ID()];
		    if($objENTITIES->Get_Type() == "user")
		    {
		        $default = $objENTITIES->Get_ID();
		    }
		}
		
		$objFORM->Add_select("Company", "entity", "", "", $default, "", $clients, $set);
		
		$set["occupy"] = "whole";
		$set["group"] = "1";
		$objFORM->Add_textbox("Company Name", "cname", "background-color: #fdffa8;", "", "", "", "", $set);
		$objFORM->Add_textbox("Company Email", "cemail", "background-color: #fdffa8;", "", "", "", "", $set);
		
		$set["occupy"] = "6";
		$objFORM->Add_textbox("Company Phone Number", "cphone", "background-color: #fdffa8;", "", "", "", "", $set);
		$objFORM->Add_textbox("Company Fax Number", "cfax", "background-color: #fdffa8;", "", "", "", "", $set);
		
		$set["occupy"] = "whole";
		$objFORM->Add_textarea("Company Address", "caddress", "background-color: #fdffa8;", "", "", "", "", $set);
		
		$set["occupy"] = "4";
		$objFORM->Add_textbox("City", "ccity", "background-color: #fdffa8;", "", "", "", "", $set);
		$objFORM->Add_textbox("State", "cstate", "background-color: #fdffa8;", "", "", "", "", $set);
		$objFORM->Add_textbox("Zip", "czip", "background-color: #fdffa8;", "", "", "", "", $set);

		
		
		$this->arrViewData["userForm"] = $objFORM->Export_Form();
		
		$menu = "sidemenu";
		$this->objRegistry->Clear_Menu($menu);
		//Add_Menu_Item($MENU, $ITEM_TEXT, $ITEM_URL, $ITEM_TYPE="link", $ICON="", $AUX_PROPS)
		$this->objRegistry->Add_Menu_Item($menu, "Back", $objURL->Make("Omega", "ListUsers", "", ""), "link", "", ["onclick" => ""]);
		
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
            		<h2>Add User</h2>
            	</div>
			</div>
            <div class="row">
                <div class="col-lg-3">
                </div>
            	<div class="col-lg-6">
<?php 	
        $DATA = &$this->arrViewData["userForm"];
        include 'Views/view_form.php';
?>
				</div>
        		<div class="col-lg-3">
        		</div>
			</div>	
		</div>
	</div>
</div>	
<?php 
        $this->display_javascript();
        return true;
	}
	
	public function display_javascript()
	{
	    ?>
		<script>
			$(document).ready(
				function() {
					$(".group1").hide();
					$("#sel_entity").change(
						function() {
							if($(this).val() == "<?php echo NULL_ENTITY_ID?>")
							{
								$(".group1").show();
							}else{
								$(".group1").hide();							
							}    			 	
						}
					);
					$("#txt_firstname, #txt_lastname").change(
							function() {
								var fname = $("#txt_firstname").val();
								var lname = $("#txt_lastname").val();
								var uname = fname.substring(0, 1)+lname;
								$("#txt_username").val(uname.toLowerCase());
							}						
					);				
				}
			);			
		</script>

	<?php
	}
}

