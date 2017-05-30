<?php

class WDIControllerSettings_wdi{
////////////////////////////////////////////////////////////////////////////////////////
// Constructor & Destructor                                                           //
////////////////////////////////////////////////////////////////////////////////////////
	function __construct(){

	}
 ////////////////////////////////////////////////////////////////////////////////////////
 // Public Methods                                                                     //
 ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    
    $this->display();
  }
  public function display(){
  	require_once(WDI_DIR . '/admin/models/WDIModelSettings_wdi.php');
  	$model = new WDIModelSettings_wdi();
  	
  	require_once(WDI_DIR . '/admin/views/WDIViewSettings_wdi.php');
  	$view = new WDIViewSettings_wdi($model);
  	$view->display();
  }	
}
