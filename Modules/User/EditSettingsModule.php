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

class EditSettingsModule extends AbstractModule
{

	public function run($PARAMETERS="")
	{
	    if(!isset($objURL)) $objURL = new \Handlers\Util\UrlHandler($this->objRegistry);	        
        if(!isset($objENTITIES)) $objENTITIES = new \Handlers\Financial\EntityHandler($this->objDatabase, $this->objRegistry);            
        if(!isset($objCATEGORIES)) $objCATEGORIES = new \Handlers\Financial\CategoriesHandler($this->objDatabase);
        if(!isset($objUSERS)) $objUSERS = new \Handlers\Users\UserHandler($this->objDatabase, $this->objRegistry);
                
        $this->objRegistry->Set_Value("SITE:CURRENT_APP", "");
        $submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
        $USER_ID = $this->objRegistry->Get_Value("USER:ID");
                 
        $this->Mode = "";
        
        $objUSERS->Load($USER_ID);
        $companyID = $objUSERS->Get_Company();
        
        //####################################################	PROCESS REQUEST
        
        if($submittedData["act"] == "edit" && $submittedData["modified"] > 0)
        {
            //DebugHandler::Display_Array($submittedData, true);
            if(!empty($companyID))
            {
                $objENTITIES->Load($companyID);
            }
            // Check that data belongs to user
	                    
            foreach ($submittedData as $key => $val)
            {
                switch($key)
                {
                    case "frm-name":
                        $objENTITIES->Set_Name($val);
                        break;
                    case "frm-phone":
                        $objENTITIES->Set_Phone($val);
                        break;
                    case "frm-fax":
                        $objENTITIES->Set_Fax($val);
                        break;
                    case "frm-address":
                        $objENTITIES->Set_Address($val);
                        break;
                    case "frm-city":
                        $objENTITIES->Set_City($val);
                        break;
                    case "frm-state":
                        $objENTITIES->Set_State($val);
                        break;
                    case "frm-zip":
                        $objENTITIES->Set_Zip($val);
                        break;
                    case "frm-contactname":
                        $objENTITIES->Set_Contact_Name($val);
                        break;
                    case "frm-contactemail":
                        $objENTITIES->Set_Email($val);
                        break;
                }
            }
	                    
            $objENTITIES->Commit();
	                    
            if($updateTags)
            {
                if(!isset($objTRANSACTIONS)) $objTRANSACTIONS = new \Handlers\Financial\TransactionsHandler($this->objDatabase);
                
                $objTRANSACTIONS->Set_User($USER_ID);
                $objTRANSACTIONS->Set_Participating_Entity(0);
                $objTRANSACTIONS->Search();
                
                while ($objTRANSACTIONS->Fetch())
                {
                    $matched = $objENTITIES->match_entity($USER_ID, $objTRANSACTIONS->Get_Description());
                    if($matched["id"] != 0)
                    {
                        $objTRANSACTIONS->Set_Participating_Entity($matched["id"]);
                        $objTRANSACTIONS->Commit();
                    }
                }
                
            }
        }
	                
        //####################################################	SETUP FORM
            
        if(!empty($companyID))
        {
            $objENTITIES->Load($companyID);               
            
            $notes = $objENTITIES->Get_Notes();
            $name = $objENTITIES->Get_Name();
            $contactName = $objENTITIES->Get_Contact_Name();
            $contactEmail = $objENTITIES->Get_Email();
            $address = $objENTITIES->Get_Address();
            $city = $objENTITIES->Get_City();
            $state = $objENTITIES->Get_State();
            $zip = $objENTITIES->Get_Zip();
            $phone = $objENTITIES->Get_Phone();
            $fax = $objENTITIES->Get_Fax();
        }

        //#######################################################################
	         
	                    
        $this->arrFormData["formCompany"]["name"] = "editCompany";
        $this->arrFormData["formCompany"]["title"] = "Company";
        $this->arrFormData["formCompany"]["submitText"] = "UPDATE";
        $this->arrFormData["formCompany"]["action"] = $objURL->Make("User", "EditSettings", "");
        $this->arrFormData["formCompany"]["hidden"] = ["name" => "act", "value" => "editCompany"];
	                    
        $row[] = array("name" => "frm-name", "label" => "Name", "value" => $name, "type" => "text", "style" => "" );
        $this->arrFormData["formCompany"]["rows"][] = $row; unset($row);
        
        $row[] = array("name" => "frm-phone", "label" => "Phone", "value" => \Handlers\Util\StringsHandler::formatPhoneNumber($phone), "type" => "phone", "style" => "" );
        $row[] = array("name" => "frm-fax", "label" => "Fax", "value" => \Handlers\Util\StringsHandler::formatPhoneNumber($fax), "style" => "", "type" => "text", "options" => "");
        $this->arrFormData["formCompany"]["rows"][] = $row; unset($row);
        
        $row[] = array("name" => "frm-address", "label" => "Address", "value" => $address, "type" => "text", "style" => "" );
        $this->arrFormData["formCompany"]["rows"][] = $row; unset($row);
        
        $row[] = array("name" => "frm-city", "label" => "City", "value" => $city, "type" => "text", "style" => "" );
        $row[] = array("name" => "frm-state", "label" => "State", "value" => $state, "type" => "text", "style" => "" );
        $row[] = array("name" => "frm-zip", "label" => "Zip", "value" => $zip, "type" => "text", "style" => "" );
        $this->arrFormData["formCompany"]["rows"][] = $row; unset($row);
        
        $row[] = array("name" => "frm-contactname", "label" => "Contact Name", "value" => $contactName, "type" => "text", "style" => "" );
        $row[] = array("name" => "frm-contactemail", "label" => "Contact Email", "value" => $contactEmail, "style" => "", "type" => "text", "options" => "");
        $this->arrFormData["formCompany"]["rows"][] = $row; unset($row);
               
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
                $DATA = &$this->arrFormData["formCompany"];
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
