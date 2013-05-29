<?php

/**
 * @package shopkeeper
 */

require_once dirname(__FILE__) . '/model/shopkeeper.class.php';
require_once dirname(__FILE__) . '/model/shk_mgr.class.php';

abstract class shkBaseManagerController extends modExtraManagerController {
    
    public $shk_manager;
    public function initialize() {
        
        $this->shk_manager = new SHKmanager($this->modx);
        
        $this->shk_manager->config['corePath'] = $this->modx->getOption('core_path').'components/shopkeeper/';
        $this->shk_manager->config['assetsUrl'] = $this->modx->getOption('assets_url').'components/shopkeeper/';
        $this->shk_manager->config['controllersPath'] = $this->shk_manager->config['corePath'].'controllers/';
        $this->shk_manager->config['connectorUrl'] = $this->shk_manager->config['assetsUrl'].'connector.php';
        $this->shk_manager->config['cssUrl'] = $this->shk_manager->config['assetsUrl'].'css/';
        $this->shk_manager->config['jsUrl'] = $this->shk_manager->config['assetsUrl'].'js/';
        $this->shk_manager->config['templatesPath'] = $this->shk_manager->config['corePath'].'templates/';
        
        $this->shk_manager->getModConfig();
        
        $this->addJavascript($this->shk_manager->config['assetsUrl'].'js/mgr/shk_mgr.js');
        $this->addCss($this->shk_manager->config['assetsUrl'].'css/mgr.css');
        
        $menu = $this->modx->getObject('modMenu',array('action'=>$_GET['a']));
        
        $this->addHtml('<script type="text/javascript">
        SHK.config = '.$this->modx->toJSON(array_merge($this->shk_manager->config,array('modName'=>$menu->get('text')))).';
        SHK.config.connector_url = "'.$this->shk_manager->config['connectorUrl'].'";
        SHK.request = '.$this->modx->toJSON($_GET).';
        </script>');
        
        return parent::initialize();
    
    }
    
    public function getLanguageTopics() {
        return array('shopkeeper:default');
    }
    
    public function checkPermissions() {
        return true;
    }
    
}

class IndexManagerController extends shkBaseManagerController {
    public static function getDefaultController() { return 'home'; }
}
