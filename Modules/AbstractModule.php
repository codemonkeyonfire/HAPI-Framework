<?php

abstract class AbstractModule
{
	protected $objDatabase;
	protected $objRegistry;
	protected $arrFormData;
	protected $arrViewData;
	protected $Mode;
	protected $MiscJS;
	public $template;
	
	abstract public function run($PARAMETERS="");
	abstract public function display($PARAMETERS="");
	
	public function __construct(&$REGISTRY="", &$DATABASE="")
	{
		$this->objDatabase = &$DATABASE;
		$this->objRegistry = &$REGISTRY;
		
		$this->template = "oneColumnWithSideMenu";
	}
	
	protected function get_view()
	{
		
		
	}
	
	protected function dump_array($ARRAY, $DIE=false)
	{
		if($DIE)
			die("<pre>".print_r($ARRAY, true)."</pre>");
		
		echo "<pre>".print_r($ARRAY, true)."</pre>";
	}
}
?>