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

class ChangePasswordModule extends AbstractModule
{

	public function run($PARAMETERS="")
	{
		
	    if(!isset($objUSER)) $objUSER = new \Handlers\Users\UserHandler($this->objDatabase, $this->objRegistry);
	    if(!isset($objValidate)) $objValidate = new \Handlers\General\ValidationHandler($this->objDatabase, $this->objRegistry);
	    if(!isset($objURL)) $objURL = new \Handlers\Util\UrlHandler($this->objRegistry);
			
		$submittedData = $this->objRegistry->Get_Value("REQUEST:DATA");
		$USER_ID = $this->objRegistry->Get_Value("USER:ID");
		
		$objUSER->Load($USER_ID);
		
//####################################################	PROCESS REQUEST

		if($submittedData["act"] == "change")
		{					
			if($submittedData["password1"] == $submittedData["password2"])
			{
				$objUSER->Set_Password($USER_ID, $submittedData["password1"]);
				$this->arrViewData["alert"]["body"] = "Password change sucessful. <a href='".$objURL->Make("", "")."' class='alert-link pull-right'>CONTINUE</a>";
				$this->arrViewData["alert"]["state"] = "success";
				return;
			}else{
				$this->arrViewData["alert"]["body"] = "Passwords do not match. ";
				$this->arrViewData["alert"]["state"] = "danger";
			}
		}
		
		if($submittedData["act"] == "send")
		{
			require_once("Handlers/General/h_Strings.php");
			require_once("Handlers/Util/h_Emails.php");
			if(!isset($objEmail)) $objEmail = new \Handlers\Util\EmailHandler($this->objDatabase, $this->objRegistry);
		
			$objValidate->Reset_User($USER_ID);
			$objValidate->Set_User($USER_ID);
			$objValidate->Set_Type("password");
			$token = \Handlers\Util\StringsHandler::get_random_string(16, "ALPHA_NUM");
			$objValidate->Set_Code($token);
			$objValidate->Commit();
				
			$objEmail->Send_Change_Password($objUSER->Get_Email(), $token, $objURL->Make("user", "changePassword", "", "token={$token}"));
				
			$this->arrViewData["alert"]["body"] = "New code sent to your email address: {$objUSER->Get_Email()}";
			$this->arrViewData["alert"]["state"] = "success";
			
			$this->arrViewData["javascript"] = "window.history.replaceState( {} , 'change password', '".$objURL->Make("user", "changePassword")."' );";
		}
//####################################################	SETUP FORM		

		require_once("Handlers/Forms/FormHandler.php");
		if(!isset($objFORM)) $objFORM = new \Handlers\Forms\FormHandler($this->objRegistry, "change_password", $objURL->Make("user", "changePassword"));
		
		$objFORM->Set_Submit_Text("Submit");
		$objFORM->Add_Hidden_Value("act", "change");

		$objFORM->Add_Password("New Password", "password1", "", "", "", "", "", ["occupy" => "whole"]);
		$objFORM->Add_Password("Re-type Password", "password2", "", "", "", "", "", ["occupy" => "whole"]);

		$this->arrViewData["passwordForm"] = $objFORM->Export_Form();
		
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

?>
    <div class="container-fluid">

      <div class="row">
		<div class="col-lg-4">
<?php if(!empty($this->arrViewData["alert"]["body"])){?>
			<div style="margin-top: 10px;">
	<?php
				$DATA = &$this->arrViewData["alert"];
				include("Views/AlertView.php");
	?>
			</div>
<?php 
}
    if(!empty($this->arrViewData["passwordForm"]))
    {
            $DATA = &$this->arrViewData["passwordForm"];
            include 'Views/view_form.php';
    }
?>
		</div>
		<div class="col-lg-8">
		<div class="input-group mb-3">
		</div>
	</div>	
<?php 
	}
}

