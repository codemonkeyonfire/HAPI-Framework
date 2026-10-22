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
 
//**************************************************************************************** 
namespace Modules\Navigation;

use Modules\AbstractModule;

class NavMenuModule extends AbstractModule
{
	public function run($PARAMETERS="")
	{
	    if(!isset($objUserRoles)) $objUserRoles = new \Handlers\Users\UserRolesHandler($this->objDatabase, $this->objRegistry);
	    if(!isset($objMenuItem)) $objMenuItem = new \Handlers\General\MenuItemHandler($this->objDatabase, $this->objRegistry);
	    if(!isset($objRoleDefs)) $objRoleDefs = new \Handlers\Users\RoleDefsHandler($this->objDatabase, $this->objRegistry);
	    if(!isset($objUrl)) $objUrl = new \Handlers\Util\UrlHandler($this->objRegistry);
	    
	    $role_admin = $objRoleDefs->Get_ID("admin");
	    $role_guest = $objRoleDefs->Get_ID("guest");
	    $active_link = $this->objRegistry->Get_Value("SITE:ACTIVE_LINK");
	    
	    $this->arrViewData["list_name"] = $PARAMETERS["displayText"];
	    
	    $listItems = $this->objRegistry->Get_Menu_Items($PARAMETERS["menu"]);
	    
	    if(!empty($listItems))
	    {

	        $this->arrData["list_items"] = $listItems;
	    }else{
	        $objMenuItem->Load_Menu($PARAMETERS["menu"], 0);
	        
	        $i = 0;
	        while($objMenuItem->Fetch())
	        {
	            $view_roles = $objMenuItem->Get_Roles_View();
	            
	            if($objMenuItem->Get_Status() == "active" && $objUserRoles->has_one_role($view_roles))
	            {
	                $buffer = ["title" => $objMenuItem->Get_Text(), "type" => $objMenuItem->Get_Type(), "link" => "", "onclick" => "", "icon" => ""];
	                
	                if($objMenuItem->Get_Type() == "link")
	                {
	                    $part = ["", "", ""];
	                    $part = explode("/", $objMenuItem->Get_Action());
	                    $buffer["link"] = $objUrl->Make($part[1], $part[2], $part[3]);
	                    
	                    $buffer["active"] = ($objMenuItem->Get_Name() == $active_link)?"active":"";
	                }else{
	                    $buffer["link"] = $objMenuItem->Get_Action();
	                }
	                
	                $this->arrData["list_items"][] = $buffer;
	            }
	        }
	    }
	}
	
	public function display($PARAMETERS="")
	{
	    switch($PARAMETERS["orient"])
	    {
	        case "horizontal":
	            $this->display_horizontal();
	            break;
	        case "vertical":
	            $this->display_vertical();
	            break;
	        case "hidden":
	            $this->display_hide();
	            break;
	        case "simple":
	            $this->display_simple();
	            break;
	        case "dropdown":
	            $this->display_dropdown($PARAMETERS);
	            break;
	        default:
	            break;
	    }
	    
	    return true;
	}
	
	private function display_dropdown($PARAMETERS="")
	{
	    $displayText = (!empty($PARAMETERS["displayText"]))?$PARAMETERS["displayText"]:$this->arrViewData["list_name"];
?>	
			<ul class="navbar-nav mb-2 mb-lg-0">
            	<li class="nav-item dropdown">
              		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
               			<?php echo $displayText?>
              		</a>
                  	<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
<?php foreach ($this->arrData["list_items"] as $item){ ?>        
	<?php if ($item["type"] == "hr"){?>
						<li><hr class="dropdown-divider"></li>
	<?php }else{ ?>
						<li><a class="dropdown-item" href="<?php echo $item["link"]; ?>"><?php echo $item["title"]; ?></a></li>
	<?php } ?>
<?php } ?>         	
                  	</ul>
       			</li>
       		</ul>
<?php     
	}
	
	private function display_simple()
	{
?>
<!-- NAV_MENU START -->
<?php 

			foreach ($this->arrData["list_items"] as $item)
			{
			    switch ($item["type"])
			    {
			        case "label":
			            ?><div class="nav-label"  style="" ><?php echo $item["title"] ?></div><?php	
                        break;
                    case "header":
                        ?><span class="nav-header"  style="" ><?php echo $item["title"] ?></span><?php			
				        break;
			        case "link":
			            if(!empty($item["icon"]))
			            {
			                ?><i class="fa fas-<?php echo $item["icon"]?>"></i><?php
	                    }
			            ?><a class="" style="" href='<?php echo $item["link"] ?>' onclick="<?php echo $item["onclick"] ?>"><?php echo $item["title"] ?></a><?php			
				        break;
                    case "extLink":
                        ?><a class="" style="" href='<?php echo $item["link"] ?>' onclick="<?php echo $item["onclick"] ?>" target="_blank"><?php echo $item["title"] ?></a><?php
                    	break;	
                    case "data":
                        ?><div class="nav-item"  style="" ><?php echo $item["title"] ?> <span style="color: #000;"><?php echo $item["value"] ?></span></div><?php			
                    	break;
                    case "hr":
                        ?><div class="divider"  style="" >&nbsp;</div><?php			
                    	break;
                    case "space":
                        ?><div class="nav-item"  style="<?php echo (isset($item["height"]))?"height:".$item["height"].";":"";?>" >&nbsp;</div><?php
                    	break;
			    }
			} 
?>		
		<!-- NAV_MENU END -->
<?php					
	}	

	private function display_horizontal()
	{
?>	    
	    <ul class="navbar-nav navbar-light me-auto mb-2 mb-lg-0">
<?php foreach($this->arrData["list_items"] as $item){?>
			<?php $this->display_item($item)?>
<?php } ?>
		</ul>
<?php 			
	}
	
	private function display_vertical()
	{
?>
<!-- MAIN_MENU START -->
		<ul class="nav flex-column">
<?php 
			foreach ($this->arrData["list_items"] as $item)
			{
				$this->display_item($item);
				if(!empty($item["children"]))
				{
					foreach ($item["children"] as $child)
					{
						$this->display_item($child, "sub");
					}
				}
			} 
?>		
		</ul>		
		<!-- MAIN_MENU END -->
<?php					
	}	
	
	private function display_item($DATA, $MODE="top")
	{
	    if(!empty($DATA["icon"]))
	    {
	        ?><i class="fa fas-<?php echo $DATA["icon"]?>"></i><?php
	    }
     
	    switch ($DATA["type"])
	    {
	        case "label":
?>
			<li class="nav-item nav-label"  style="" ><?php echo $DATA["title"] ?></li>	
<?php			
				break;
            case "header":
?>
			<li class="nav-item nav-header"  style="" ><?php echo $DATA["title"] ?></li>	
<?php			
				break;
			case "link":
?>
			<li class="nav-item"  style="" ><a class="nav-link <?php if($DATA["active"]){echo "active";}?>" <?php if($DATA["disabled"]){echo "disabled";}?> style="" href='<?php echo $DATA["link"] ?>' onclick="<?php echo $DATA["onclick"] ?>"><?php echo $DATA["title"] ?></a></li>	
<?php			
				break;
			case "extLink":
?>
			<li class="nav-item"  style="" ><a class="nav-link <?php if($DATA["disabled"]){echo "disabled";}?> style="" href='<?php echo $DATA["link"] ?>' onclick="<?php echo $DATA["onclick"] ?>"><?php echo $DATA["title"] ?></a></li>	
<?php			
				break;	
			case "data":
?>
			<li class="nav-item"  style="" ><?php echo $DATA["title"] ?> <span style="color: #000;"><?php echo $DATA["value"] ?></span></li>	
<?php			
				break;
            case "hr":
?>
			<li class="nav-item"  style="" ><hr></li>		
<?php			
				break;
            case "space":
?>
			<li class="nav-item"  style="" >&nbsp;</li>	
<?php			
				break;
		}

	}

	private function display_hide()
	{
?>
	<script>
		$("#interface-left").css("width", "10px");
	</script>
<?php		
	}
}
