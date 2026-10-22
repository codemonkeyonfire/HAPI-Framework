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
namespace Modules\User;

use Modules\AbstractModule;

class EditProfileModule extends AbstractModule
{

	public function run($PARAMETERS="")
	{
	
	    if(!isset($objURL))	$objURL = new \Handlers\Util\UrlHandler($this->objRegistry);		
		if(!isset($objENTITIES)) $objENTITIES = new \Handlers\Financial\EntityHandler($this->objDatabase, $this->objRegistry);		
		if(!isset($objUSERS)) $objUSERS = new \Handlers\Users\UserHandler($this->objDatabase, $this->objRegistry);
					
		$this->objRegistry->Set_Value("SITE:CURRENT_APP", "");
		$submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
		$USER_ID = $this->objRegistry->Get_Value("USER:ID");
		
		if(empty($USER_ID)) 
		{
			die("<div>Missing ID</div><div><a href='".$objURL->Make()."'>BACK</a></div>");
		}
		
		$this->Mode = "";
		
//####################################################	PROCESS REQUEST
			
		if($submittedData["act"] == "edit" && $submittedData["modified"] > 0)
		{
			//DebugHandler::Display_Array($submittedData, true);
				
		    $objUSERS->Load($USER_ID);
			
			foreach ($submittedData as $key => $val)
			{
				switch($key)
				{
					case "frm-firstname":
					    $objUSERS->Set_First_Name($val);
						break;
					case "frm-lastname":
					    $objUSERS->Set_Last_Name($val);
						break;
					case "frm-email":
					    $objUSERS->Set_Email($val);
					    break;
					case "frm-phone":
					    $objUSERS->Set_Phone($val);
						break;
					case "frm-fax":
					    $objUSERS->Set_Fax($val);
						break;
					case "frm-address":
					    $objUSERS->Set_Address($val);
						break;
					case "frm-city":
					    $objUSERS->Set_City($val);
					    break;
					case "frm-state":
					    $objUSERS->Set_State($val);
					    break;
					case "frm-zip":
					    $objUSERS->Set_Zip($val);
					    break;
				}
			}
			$objUSERS->Commit();
			
		}
		
//####################################################	SETUP FORM		
		
		$objUSERS->Load($USER_ID);
		$companyID = $objUSERS->Get_Company();
				
		$this->arrFormData["name"] = "edit";
		$this->arrFormData["title"] = "Profile";
		$this->arrFormData["submitText"] = "UPDATE";
		$this->arrFormData["action"] = $objURL->Make("User", "EditProfile");
		
		$row[] = array("name" => "frm-firstname", "label" => "First Name", "value" => $objUSERS->Get_First_Name(), "type" => "text", "style" => "" );
		$row[] = array("name" => "frm-lastname", "label" => "Last Name", "value" => $objUSERS->Get_Last_Name(), "type" => "text", "style" => "" );
		$this->arrFormData["rows"][] = $row; unset($row);
		
		$row[] = array("name" => "frm-username", "label" => "Username", "value" => $objUSERS->Get_User_Name(), "type" => "readonly", "style" => "" );
		$row[] = array("name" => "frm-email", "label" => "Email", "value" => $objUSERS->Get_Email(), "style" => "", "type" => "text", "options" => "");
		$this->arrFormData["rows"][] = $row; unset($row);
		
		$row[] = array("name" => "frm-phone", "label" => "Phone", "value" => \Handlers\Util\StringsHandler::formatPhoneNumber($objUSERS->Get_Phone()), "type" => "phone", "style" => "" );
		$row[] = array("name" => "frm-fax", "label" => "Fax", "value" => \Handlers\Util\StringsHandler::formatPhoneNumber($objUSERS->Get_Fax()), "style" => "", "type" => "text", "options" => "");
		$this->arrFormData["rows"][] = $row; unset($row);
		
		$row[] = array("name" => "frm-address", "label" => "Address", "value" => $objUSERS->Get_Address(), "type" => "text", "style" => "" );
		$this->arrFormData["rows"][] = $row; unset($row);
		
		$row[] = array("name" => "frm-city", "label" => "City", "value" => $objUSERS->Get_City(), "type" => "text", "style" => "" );
		$row[] = array("name" => "frm-state", "label" => "State", "value" => $objUSERS->Get_State(), "type" => "text", "style" => "" );
		$row[] = array("name" => "frm-zip", "label" => "Zip", "value" => $objUSERS->Get_Zip(), "type" => "text", "style" => "" );
		$this->arrFormData["rows"][] = $row; unset($row);
		
		$row[] = array("name" => "frm-company", "label" => "", "value" => $objUSERS->Get_Company(), "type" => "hidden", "style" => "" );
		
		$menu = "sidemenu";
		$this->objRegistry->Clear_Menu($menu);
		//Add_Menu_Item($MENU, $ITEM_TEXT, $ITEM_URL, $ITEM_TYPE="link", $ICON="", $AUX_PROPS)
		$this->objRegistry->Add_Menu_Item($menu, "Back", $objURL->Make("General", "dashboard", "", ""), "link", "", ["onclick" => ""]);
	}
	
	public function display($PARAMETERS="")
	{
		if($this->objRegistry->Get_Value("IS_LIVE:EDIT_CASHFLOW") == "NO")
		{
			echo "This Page is currently unavailable";
			exit();
		}
	
		switch ($this->Mode)
		{
			case "message":
				echo $this->arrFormData["message"];
			default:
?>
	<div class="row">
		<div class="col-12 col-md-6 border-bottom border-end">
			<div class="content_item">
<?php 
				$DATA = &$this->arrFormData;
				include("Views/v_Edit.php");
?>
			</div>
		</div>
	</div>
<?php 
				break;
		}
			
		return true;
	}	
	
}
