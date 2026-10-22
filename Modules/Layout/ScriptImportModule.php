<?php
 /*************************************************************************
 * 
 *  (c)2015 Carlos Ferrera
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
namespace Modules\Layout;

use Modules\AbstractModule; 

class ScriptImportModule extends AbstractModule 
{
		
	public function run($PARAMETERS="")
	{
	    $basepath = $this->objRegistry->Get_Value("SITE:BASE_PATH");
	    
	    if($basepath == "/")
	    {
    		$this->arrFormData["js_footer"] = $this->objRegistry->Get_Value("JAVASCRIPT:INCLUDE@FOOTER");
    		$this->arrFormData["js_header"] = $this->objRegistry->Get_Value("JAVASCRIPT:INCLUDE@HEADER");
    		$this->arrFormData["css"] = $this->objRegistry->Get_Value("CSS:INCLUDE");
	    }else{
	        $basepath = rtrim($basepath, "/");
	        foreach($this->objRegistry->Get_Value("JAVASCRIPT:INCLUDE@FOOTER") as $js)
	        {
	            $this->arrFormData["js_footer"][] = $basepath.$js;
	        }
	        foreach($this->objRegistry->Get_Value("JAVASCRIPT:INCLUDE@HEADER") as $js)
	        {
	            $this->arrFormData["js_header"][] = $basepath.$js;
	        }
	        foreach($this->objRegistry->Get_Value("CSS:INCLUDE") as $css)
	        {
	            $this->arrFormData["css"][] = $basepath.$css;
	        }
	    }
	}
	
	public function display($PARAMETERS="")
	{
	    
	    if(empty($PARAMETERS["output"]) || $PARAMETERS["output"] == "css")
		{

	        foreach ($this->arrFormData["css"] as $scipt)
	        {
?>
				<link rel="stylesheet" type="text/css" href="<?php echo $scipt?>" />
<?php
            }	
		}
		
		if(empty($PARAMETERS["output"]) || $PARAMETERS["output"] == "js")
		{
		    $loc = (!empty($PARAMETERS["location"]))?$PARAMETERS["location"]:"footer";
		    foreach ($this->arrFormData["js_".$loc] as $scipt)
            {
?>
				<script type="text/javascript" src="<?php echo $scipt?>"></script> 	
<?php
            }

		}
		
		return true;
	}


}
