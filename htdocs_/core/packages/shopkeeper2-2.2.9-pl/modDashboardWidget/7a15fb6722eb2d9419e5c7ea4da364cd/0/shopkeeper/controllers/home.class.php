<?php

/**
 * Loads the home page.
 * 
 * @package shopkeeper
 * @subpackage controllers
 */

class shopkeeperHomeManagerController extends shkBaseManagerController {
    
    public function process(array $scriptProperties = array()) {

    }
    
    public function getPageTitle() {
        return $this->modx->lexicon('shopkeeper');
    }
    
    public function loadCustomCssJs() {
        
        $this->addJavascript($this->shk_manager->config['jsUrl'].'mgr/widgets/orders.grid.js');
        $this->addJavascript($this->shk_manager->config['jsUrl'].'mgr/widgets/configuration.panel.js');
        $this->addJavascript($this->shk_manager->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addJavascript($this->shk_manager->config['jsUrl'].'mgr/sections/index.js');
        
        //OnSHKScriptsLoad
        $OnSHKScriptsLoad = $this->modx->invokeEvent('OnSHKScriptsLoad');
        if(is_array($OnSHKScriptsLoad)){
            foreach($OnSHKScriptsLoad as $plugin_js){
                if(!empty($plugin_js)) $this->addJavascript(MODX_BASE_URL.$plugin_js);
            }
        }
        
    }
    
    public function getTemplateFile() {
        return $this->shk_manager->config['templatesPath'].'home.tpl';
    }
    
}

