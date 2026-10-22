<?php
 /*************************************************************************
 * 
 *  (c)2023 Carlos Ferrera
 *  All Rights Reserved.
 * 
 * NOTICE:  
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Carlos Ferrera.
 *
 * Email: irondogg@gmail.com
 * 
 * this is the dynamic form handler
 */
namespace Handlers\Forms;

class FormHandler
{
    protected $encType;
	protected $Name;
	protected $Target;
	protected $Method;
	protected $OnSubmit;
	protected $SubmitText;
	protected $arrExtraActions;
	public $arrFields;
	protected $arrHidden_Fields;
	protected $arrForm_Controls;
	protected $Form_Title;
	protected $Submitted_Data;
	protected $JS_Libs;
	public $JS_Fields;
	protected $Registry;
	private $numFields;

	
	public function __construct(&$SETTINGS="", $NAME="", $ACTION="", $METHOD="post", $ENCTYPE="")
	{
		//  set static settings here 
		$this->Name = $NAME;
		$this->encType = $ENCTYPE;
		$this->Target = $ACTION;
		$this->Method = $METHOD;		
		$this->Registry = &$SETTINGS;
	
		foreach ($_POST as $name => $value)
		{
			$this->Submitted_Data[$name] = $value;
		}
		
		$this->arrFields = [];
		$this->numFields = 0;
	}	
	
	public function Set_Title($TITLE)
	{
		$this->Form_Title = $TITLE;	
	}
	
	public function Set_OnSubmit($CODE)
	{
		$this->OnSubmit = $CODE;	
	}	
		
	public function Set_Submit_Text($VALUE)
	{
	    $this->SubmitText = $VALUE;
	}	
	
	public static function Get_ID_Part($TYPE)
	{
	    $inputMap = ["label" => "lbl", "select" => "sel", "text" => "txt", "check" => "chk", "textarea" => "txa", "radio" => "rad", "datepicker" => "dpk", "timepicker" => "tpk", "textedit-limited" => "txa", "textedit" => "txe", "toggle" => "tgl"];
	    $inputMap["password"] = "pwd";
	    $inputMap["spinner"] = "spr";
	    $inputMap["list"] = "lst";
	    $inputMap["tinymce"] = "mce";
	    $inputMap["colorpicker"] = "cpk";
	    $inputMap["file"] = "fil";
	    $inputMap["file_ext"] = "fxt";
	    $inputMap["ledger"] = "lgr";
	    $inputMap["multifile"] = "mfl";
	    $inputMap["tinyfilemanager"] = "tfm";
	    $inputMap["dropzone"] = "dpz";
	    $inputMap["filepond"] = "fpd";
	    $inputMap["timepickerMD"] = "tmd";
	    
	    
	    return $inputMap[$TYPE];
	}
	
	public function Add_Field($PROPERTIES)
	{
	    $fID = $this->numFields;
	    $this->arrFields[] = $PROPERTIES;
	    $this->numFields++;
	    
	    switch($PROPERTIES["type"])
	    {
	        case "label":
	            break;
	        case "colorpicker":
	            $this->JS_Fields["colorpicker"][] = "txt_".$PROPERTIES["name"];
	            $this->Registry->Add_JS("/3rdParty/jquery-ui.js", "header");
	            $this->Registry->Add_JS("/3rdParty/colorpicker/jquery.colorpicker.js", "header");
	            $this->Registry->Add_CSS("/3rdParty/colorpicker/jquery.colorpicker.css", "header");
	            $this->Registry->Add_CSS("/3rdParty/jquery-ui.min.css", "header");
	            break;
	        case "timepicker":
	            $this->Registry->Add_JS("/3rdParty/timepicker.js");
	            $this->JS_Fields["timepickers"][] = "txt_".$PROPERTIES["name"];
	            break;
	        case "datepicker":
	            $this->Registry->Add_CSS("/3rdParty/jquery-ui.css");
	            $this->Registry->Add_JS("/3rdParty/jquery-ui.js");
	            $this->JS_Fields["datepickers"][] = "txt_".$PROPERTIES["name"];
	        case "text":
	            break;
	        case "spinner":	            
	            $this->Registry->Add_JS("/3rdParty/bootstrap-input-spinner.js");
	            $this->JS_Fields["spinners"][] = "spr_".$PROPERTIES["name"];
	            break;
	        case "list":
	            $this->JS_Fields["lists"][] = "lst_".$PROPERTIES["name"];
	            break;
	        case "select":
	            break;
	        case "textedit_limited":
	            $this->Registry->Add_JS("/3rdParty/tinymce/tinymce.min.js");
	            $this->JS_Fields["charLimitedEditors"][] = "txa_".$PROPERTIES["name"];
	            break;
	        case "textedit":
	            $this->Registry->Add_JS("/3rdParty/tinymce/tinymce.min.js");
	            $this->JS_Fields["editors"][] = "txa_".$PROPERTIES["name"];
	        case "textarea":
	            break;
	        case "tinymce":
	            $this->JS_Fields["tinymce"][] = "mce_".$PROPERTIES["name"];
	            $this->Registry->Add_JS("/3rdParty/tinymce/tinymce.min.js", "header");
	            break;
	        case "toggle":
	            break;
	        case "radio":
	            break;
	        case "check":
	            break;
	        case "togglepanel":
	            break;
	        case "radiopanel":
	            break;
	        case "radiopanel_v":
	            break;
	        case "checkpanel":
	            break;
	        case "checkpanel_v":
	            break;
	        case "password":
	            $this->JS_Fields["password"][] = "pwd_".$PROPERTIES["name"];
	            break;
	        case "file":
	            $this->JS_Fields["file"][] = "fil_".$PROPERTIES["name"];
	            break;
	        case "file_ext":
	            $this->JS_Fields["fileext"][] = "fxt_".$PROPERTIES["name"];
	            break;
	        case "multifile":
	            $this->JS_Fields["multifile"] = "mfl_".$PROPERTIES["name"];
	            break;
	        case "dropzone":
	            $this->Registry->Add_CSS("/3rdParty/dropzone/min/dropzone.min.css");
	            $this->Registry->Add_JS("/3rdParty/dropzone/min/dropzone.min.js");	
	            $this->JS_Fields["dropzone"] = "fil_".$PROPERTIES["name"];
	            break;
	        case "filepond":
	            $this->JS_Fields["filepond"] = "fil_".$PROPERTIES["name"];
	            break;
	        case "formfilemanager":
	            break;
	        case "tinyfilemanager":
	            $this->JS_Fields["tinyfilemanager"][] = "tfl".$PROPERTIES["name"];
	            break;
	        case "ledger":
	            $id = (!empty($PROPERTIES["id"]))?$PROPERTIES["id"]:$PROPERTIES["name"];
	            $this->JS_Fields["ledger"][] = "lgr_".$id;
	            break;
	        case "link":
	            break;
	        case "image":
	            break;
	        case "button":
	            break;
	        case "space":
	            break;
	        case "hr":
	            break;
	        case "raw":
	            break;
	        case "gallery":
	            break;
	        default:
	    }
	    
	    return $fID;
	}	
	
	public function Add_button($LABEL, $NAME, $DISPLAY_TEXT, $STYLE="", $CLASS="", $ON_CLICK="", $VALUE="", $ATTRIBUTE="", $BUTTON_TYPE="", $OUTLINE="", $DISPLAY_PROPERTIES="")
	{
        // type = primary | secondary | success | danger | warning | info | light | dark,     outline = primary | secondary | success | danger | warning | info | light | dark,

	    $props = [];
	    $props["type"] = "button";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    $props["displayText"] = $DISPLAY_TEXT;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ON_CLICK != "") $props["onClick"] = $ON_CLICK;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($BUTTON_TYPE != "") $props["buttonType"] = $BUTTON_TYPE;
	    if($OUTLINE != "") $props["outline"] = $OUTLINE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}

	public function Add_checkbox($LABEL, $NAME, $CHECKED, $VALUE="", $ATTRIBUTE="", $HORIZONTAL="", $OPTIONS="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "check";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    $props["checked"] = $CHECKED;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($HORIZONTAL != "") $props["horizontal"] = $HORIZONTAL;
	    if($OPTIONS != "") $props["options"] = $OPTIONS;
	    	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_radiobutton($LABEL, $NAME, $CHECKED, $VALUE="", $ATTRIBUTE="", $HORIZONTAL="", $OPTIONS="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "radio";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    $props["checked"] = $CHECKED;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($HORIZONTAL != "") $props["horizontal"] = $HORIZONTAL;
	    if($OPTIONS != "") $props["options"] = $OPTIONS;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_colorpicker($LABEL, $NAME, $PLACE_HOLDER="", $STYLE="", $CLASS="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "colorpicker";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	       
	    return $this->Add_Field($props);
	}
	
	public function Add_datepicker($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "datepicker";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_file($LABEL, $NAME, $CLASS="", $PLACE_HOLDER="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "file";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_file_ext($LABEL, $NAME, $CLASS="", $PLACE_HOLDER="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "file_ext";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_formfilemanager($LABEL, $ROOT_PATH, $STYLE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "formfilemanager";
	    $props["text"] = $LABEL;

	    $props["content"] = $ROOT_PATH;
	    if($STYLE != "") $props["style"] = $STYLE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_hr($STYLE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "hr";

	    if($STYLE != "") $props["style"] = $STYLE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_html($CONTENT="", $CLASS="", $STYLE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "html";
	    $props["content"] = $CONTENT;
	    
	    if($CLASS != "") $props["classes"] = $CLASS;
	    if($STYLE != "") $props["style"] = $STYLE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}

	public function Add_image($LABEL, $SRC, $STYLE="", $CLASS="", $LINK="", $DISPLAY_PROPERTIES="" )
	{
	    $props["type"] = "image";
	    $props["text"] = $LABEL;

	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($LINK != "") $props["link"] = $LINK;
	    
	    $props["src"] = $SRC;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}

	public function Add_label($NAME, $DISPLAY_TEXT, $PLACE_HOLDER="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "label";

	    $props["name"] = $NAME;
	    $props["displayText"] = $DISPLAY_TEXT;

	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}

	public function Add_ledger($LABEL, $NAME, $VALUES, $NUM_COLS, $ID="", $TITLE="", $DISPLAY_PROPERTIES="")
	{
        $props["type"] = "ledger";
        $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    $props["values"] = $VALUES;
	    $props["cols"] = $NUM_COLS;
	    if($ID != "") $props["id"] = $ID;
	    if($TITLE != "") $props["title"] = $TITLE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}

	public function Add_link($LABEL, $URL, $DISPLAY_TEXT="", $STYLE="", $CLASS="", $ACTIVE="", $DISPLAY_PROPERTIES="")
	{	    
	    $props["type"] = "link";
	    $props["text"] = $LABEL;
	    $props["url"] = $URL;
	    if($DISPLAY_TEXT != "") $props["displayText"] = $DISPLAY_TEXT;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ACTIVE != "") $props["onClick"] = $ACTIVE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}

	public function Add_list($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $OPTIONS="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "list";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;

	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($OPTIONS != "") $props["options"] = $OPTIONS;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;

	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}

	public function Add_multifile($LABEL, $NAME, $PLACE_HOLDER="", $BRAND="multifile", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = $BRAND;  // multifile | dropzone | filepond
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
		
	public function Add_raw($LABEL, $CONTENT, $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "raw";
	    $props["text"] = $LABEL;
	    $props["content"] = $CONTENT;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_select($LABEL, $NAME, $STYLE="", $CLASS="", $VALUE="", $ATTRIBUTE="", $OPTIONS="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "select";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($OPTIONS != "") $props["options"] = $OPTIONS;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_space($STYLE, $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "space";
	    $props["style"] = $STYLE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_textbox($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props = [];
	    $props["type"] = "text";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    	       
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	       $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	 
	    return $this->Add_Field($props);
	}
	
	public function Add_Password($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props = [];
	    $props["type"] = "password";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_textarea($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "textarea";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_textedit($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $CHAR_LIMITED="false", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "textedit";
	    $props["text"] = $LABEL;
	    if($CHAR_LIMITED) $props["type"] .= "_limited";
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_timepicker($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "timepicker";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}

	
	public function Add_timepickerMD($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "timepickerMD";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    
	    $this->Registry->Add_CSS("/3rdParty/MDTimePicker/mdtimepicker.min.css");
	    $this->Registry->Add_JS("/3rdParty/MDTimePicker/mdtimepicker.min.js");
	    $this->JS_Fields["timepickerMDs"][] = "txt_".$NAME;
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_tinyfilemanager($LABEL, $ROOT_PATH, $STYLE="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "tinyfilemanager";
	    $props["text"] = $LABEL;
	    
	    $props["content"] = $ROOT_PATH;
	    if($STYLE != "") $props["style"] = $STYLE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_toggle($LABEL, $NAME, $VALUE="", $CHECKED="", $ATTRIBUTE="", $OPTIONS="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "toggle";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($OPTIONS != "") $props["options"] = $OPTIONS;
	    if($CHECKED != "") $props["checked"] = $CHECKED;

	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_spinner($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props = [];
	    $props["type"] = "spinner";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_TinyMCE($LABEL, $NAME, $STYLE="", $CLASS="", $PLACE_HOLDER="", $VALUE="", $ATTRIBUTE="", $DISPLAY_PROPERTIES="")
	{
	    $props = [];
	    $props["type"] = "tinymce";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    if($STYLE != "") $props["style"] = $STYLE;
	    if($CLASS != "") $props["class"] = $CLASS;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($PLACE_HOLDER != "") $props["placeHolder"] = $PLACE_HOLDER;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
    
	    return $this->Add_Field($props);
	}
	
	public function Add_Gallery($LABEL, $NAME, $VALUE="", $ATTRIBUTE="", $IMAGES="", $DISPLAY_PROPERTIES="")
	{
	    $props["type"] = "gallery";
	    $props["text"] = $LABEL;
	    $props["name"] = $NAME;
	    $props["images"] = $IMAGES;
	    if($VALUE != "") $props["value"] = $VALUE;
	    if($ATTRIBUTE != "") $props["attribute"] = $ATTRIBUTE;
	    
	    if(is_array($DISPLAY_PROPERTIES))
	    {
	        $props = array_merge($props, $DISPLAY_PROPERTIES);
	    }
	    
	    return $this->Add_Field($props);
	}
	
	public function Add_Hidden_Value($NAME, $VALUE="")
	{
		$this->arrHidden_Fields[] = array("name" => $NAME, "value" => $VALUE);
	}	

	public function Add_Form_Control($NAME, $ACTION="")
	{
		$this->arrForm_Controls[] = array("name" => $NAME, "onclick" => $ACTION);
	}		
	
	public function Add_Extra_Action($TEXT, $URL)
	{
	    $this->arrExtraActions[] = ["text"=> $TEXT, "url"=> $URL];
	}
		
	public function Add_Extra_Content($CONTENT)
	{
	    $this->extraContent = $CONTENT;
	}
	
	public function Set_Field_Attribute($FIELD, $PROPERTY, $VALUE)
	{
	    if(is_int($FIELD))
	    {
	        $this->arrFields[$FIELD][$PROPERTY] = $VALUE;
	    }else{	    
    	    $count = count($this->arrFields);
    	    
    	    for($i = 0; $i < $count; $i++)
    	    {
    	        if($this->arrFields[$i]["name"] == $FIELD)
    	        {
    	            $this->arrFields[$i][$PROPERTY] = $VALUE;
    	            break;
    	        }
    	    }		
	    }
	}

	public function Get_Submitted_Data($FIELD_NAME)
	{
	    if(isset($this->Submitted_Data[$FIELD_NAME]))
	        return $this->Submitted_Data[$FIELD_NAME];
		else 
			return "";
	}
	
	public function Data_Posted()
	{
		return (empty($this->Submitted_Data))?false:true;	
	}
	
	public function Export_Form()
	{
	    $DATA["name"] = $this->Name;
	    $DATA["action"] = $this->Target;
	    $DATA["method"] = $this->Method;
	    $DATA["enctype"] = $this->encType;
	    $DATA["submitText"] = $this->SubmitText;
	    $DATA["extraActions"] = $this->arrExtraActions;
	    
	    foreach ($this->arrHidden_Fields as $hVal)
	    {
	        $DATA["hiddenVals"][$hVal["name"]] = $hVal["value"];
	    }
	    
	    $DATA["sections"][] = ["title" => $this->Form_Title, "subTitle" => "", "fields" => $this->arrFields];
	    
	    $DATA["cancelUrl"] = "";
	    
	    $DATA["extra"] = $this->extraContent;
	    $DATA["includeJS"] = $this->JS_Fields;
	    
        return $DATA;	    
	}
	
//  *****************************************************************************************************************
//	functions not using loaded object
	
	public function Generate_Javascript()
	{
	
		 $output .= "
		 <script> 
		 	$('#but_submit').click(function() { 
		 		$('#frm_{$this->Name}').submit();
		 	});
		 </script>\n";		
		 	
		 return $output;
	}
}
?>
