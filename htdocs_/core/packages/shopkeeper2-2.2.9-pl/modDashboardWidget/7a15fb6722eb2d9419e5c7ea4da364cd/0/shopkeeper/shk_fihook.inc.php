<?php
/**
 * FormIt hook for Shopkeeper 2.x
 */

$output = false;
$orderData = '';

$manager_language = $modx->config['manager_language'];
$charset = $modx->config['modx_charset'];
$rb_base_url = $modx->config['rb_base_url'];
$site_url = $modx->config['site_url'];
$dbname = $modx->config['dbname'];
$dbprefix = $modx->config['table_prefix'];
$emailsender = $modx->config['emailsender'];

if(!defined('SHOPKEEPER_PATH')) define('SHOPKEEPER_PATH', MODX_CORE_PATH."components/shopkeeper/");
if(!defined('SHOPKEEPER_URL')) define('SHOPKEEPER_URL', MODX_BASE_URL."core/components/shopkeeper/");
if(!defined('SHOPKEEPER_ASSETS_PATH')) define('SHOPKEEPER_ASSETS_PATH', MODX_BASE_PATH."assets/components/shopkeeper/");
if(!defined('SHOPKEEPER_ASSETS_URL')) define('SHOPKEEPER_ASSETS_URL', MODX_BASE_URL."assets/components/shopkeeper/");

//Определяем параметры сниппета Shopkeeper
$sys_property_set_default = $modx->getOption('shk.property_set_default',$modx->config,'default');
$propertySetName = $modx->getOption('shkPropertySetName',$_SESSION,$sys_property_set_default);
$snippet = $modx->getObject('modSnippet',array('name'=>'Shopkeeper'));
$properties = $snippet->getProperties();
if($propertySetName!='default' && $modx->getCount('modPropertySet',array('name'=>$propertySetName))>0){
    $propSet = $modx->getObject('modPropertySet',array('name'=>$propertySetName));
    $propSetProperties = $propSet->getProperties();
    if(is_array($propSetProperties)) $properties = array_merge($properties,$propSetProperties);
}

$lang = $modx->getOption('lang',$properties,'ru');
$modx->getService('lexicon','modLexicon');
$modx->lexicon->load($lang.':shopkeeper:default');

//$user = $modx->getUser();
//$userId = $user->get('id');

if(!empty($_SESSION['shk_purchases'])){
    
    require_once SHOPKEEPER_PATH."model/shopkeeper.class.php";
    $shopCart = new Shopkeeper($modx,$properties);
    
    $modx->addPackage('shopkeeper',SHOPKEEPER_PATH.'model/');
    
    $purchases = $_SESSION['shk_purchases'];
    $addit_params = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : array();
    list($price_total,$items_total) = $shopCart->getTotal($purchases,$addit_params);
    
    //сохраняем имена товаров
    $contentData = $shopCart->getContentData($purchases);
    //TV параметры
    $tvNamesList = isset($properties['TVsaveList']) ? explode(',',$properties['TVsaveList']) : array();
    $templateVars = $shopCart->getTmplVars($purchases,$tvNamesList);
    
    foreach($purchases as & $prod){
        $prod['tv'] = isset($templateVars[$prod['id']]) ? (array) $templateVars[$prod['id']] : array();
        if(isset($contentData[$prod['id']]['pagetitle'])) $prod['name'] = $contentData[$prod['id']]['pagetitle'];
        ksort($prod);
    }
    
    $orderData = $shopCart->getOrderData($purchases,$addit_params);
    
    $allFormFields = $hook->getValues();
    unset($allFormFields['orderData'],$allFormFields['submit'],$allFormFields['formid'],$allFormFields['vericode'],$allFormFields['nospam:blank']);
    $contacts = serialize($allFormFields);
    
    $userId = $modx->getLoginUserID('web') ? $modx->getLoginUserID('web') : 0;
    
    //Сохраняем данные заказа
    $order = $modx->newObject('SHKorder');
    $order->fromArray(array(
        'contacts' => $contacts,
        'content' => serialize($purchases),
        'allowed' => 'all',
        'addit' => serialize($addit_params),
        'price' => $price_total,
        'currency' => $shopCart->config['currency'],
        'date' => strftime('%Y-%m-%d %H:%M:%S'),
        'sentdate' => '',
        'note' => '',
        'email' => isset($allFormFields['email']) ? $allFormFields['email'] : '',
        'phone' => isset($allFormFields['phone']) ? $allFormFields['phone'] : '',
        'delivery' => isset($allFormFields['delivery']) ? $allFormFields['delivery'] : '',
        'payment' => isset($allFormFields['payment']) ? $allFormFields['payment'] : '',
        'tracking_num' => '',
        'status' => $price_total>0 ? '0' : '5',
        'userid' => $userId
    ));

    $order->save();
    
    //OnSHKsaveOrder
    $evtOut = $modx->invokeEvent('OnSHKsaveOrder',array('order_id' => $order->get('id')));
    if(is_array($evtOut)) $orderData .= implode('',$evtOut);
    
    //OnSHKsendOrderMail
    $shk_plugin = '';
    $evtOut = $modx->invokeEvent('OnSHKsendOrderMail',array('order_id' => $order->get('id')));
    if(is_array($evtOut)) $shk_plugin = implode('',$evtOut);
    
    $hook->setValues(array(
        'orderID' => $order->get('id'),
        'orderData' => $orderData,
        'date' => $order->get('date'),
        'shk_plugin' => $shk_plugin
    ));
    
    //сохраняем данные последнего заказа в сессию
    $shopCart->setOrderDataSession($order->toArray());
    
    //очищаем корзину
    $shopCart->emptySavedData();
    
    $output = true;
  
}else{
    
    $orderData = "<i>".$modx->lexicon('shk.no_selected')."</i>";
    $hook->addError('error_message',$modx->lexicon('shk.order_empty'));
    $output = false;
    
}

return $output;