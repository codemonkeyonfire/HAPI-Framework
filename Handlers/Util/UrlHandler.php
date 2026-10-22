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
namespace Handlers\Util;

class UrlHandler
{
	private $protocol;
	private $hostname;
	private $base_path;
	private $port;
	private $scriptname;
	
	public function __construct(&$SETTINGS="")
	{
		$this->protocol = $SETTINGS->Get_Value("SITE:PROTOCOL");
		$this->hostname = $SETTINGS->Get_Value("SITE:HOSTNAME");
		$this->base_path = $SETTINGS->Get_Value("SITE:BASE_PATH");
		$this->port = $SETTINGS->Get_Value("SITE:PORT");
		$this->scriptname = $SETTINGS->Get_Value("SITE:SCRIPTNAME");
	}
	
	public function Make($APP="General", $MAIN_MOD="dashboard", $ITEM="", $DATA="")
	{
		$url = $this->protocol;
		$url .= $this->hostname;
		$url .= (!empty($this->port))?":".$this->port:"";
		$url .= $this->base_path."{$APP}/{$MAIN_MOD}";
		$url .= (!empty($ITEM))?"/{$ITEM}":"";
		$url .= (!empty($DATA))?"?{$DATA}":"";
		if(!empty($_REQUEST["dbug"]))
		{
		    $url .= (!empty($DATA))?"&":"?";
            $url .= "dbug=1";
		}
		
		return $url;
	}
	
	public function Root()
	{
		$url = $this->protocol;
		$url .= $this->hostname;
		$url .= (!empty($this->base_path))?$this->base_path:"";
		$url .= (!empty($this->port))?":".$this->port:"";

		return $url;
	}
}
?>