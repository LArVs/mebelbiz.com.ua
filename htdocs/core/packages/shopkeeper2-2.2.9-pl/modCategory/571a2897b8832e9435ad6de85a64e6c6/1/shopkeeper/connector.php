<?php
/**
 * Shopkeeper Connector
 *
 * @package shopkeeper
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('core_path').'components/shopkeeper/';

//require_once $corePath.'model/shk_mgr.class.php';
//$SHKmanager = new SHKmanager($modx);

$modx->addPackage('shopkeeper',$modx->getOption('core_path').'components/shopkeeper/model/');

$modx->lexicon->load('shopkeeper:default');

/* handle request */
$modx->request->handleRequest(array(
    'processors_path' => $modx->getOption( 'core_path' ) . 'components/shopkeeper/processors/',
    'location' => ''
));

