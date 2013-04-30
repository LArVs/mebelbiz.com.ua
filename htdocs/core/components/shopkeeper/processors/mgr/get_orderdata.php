<?php

/**
 * @package shopkeeper
 * @subpackage processors
 */

$orderData = '';
$contacts = '';

if (empty($scriptProperties['id'])) return $modx->error->failure('Invalid data.');
$order_id = $scriptProperties['id'];
$order = $modx->getObject('SHKorder',$order_id);
if (empty($order)) return $modx->error->failure('Invalid data 2.');

if($order->get('content')){
    
    require_once MODX_CORE_PATH."components/shopkeeper/model/shopkeeper.class.php";
    require_once MODX_CORE_PATH."components/shopkeeper/model/shk_mgr.class.php";
    $SHKmanager = new SHKmanager($modx);
    $SHKmanager->getModConfig();
    $SHKmanager->config['tplPath'] = isset($SHKmanager->config['tpl_path']) ? $SHKmanager->config['tpl_path'] :'core/components/shopkeeper/elements/chunks/ru/';
    $SHKmanager->config['orderDataTpl'] = $SHKmanager->config['order_data_tpl'];
    $SHKmanager->config['additDataTpl'] = $SHKmanager->config['addit_data_tpl'];
    
    //Товары и доп.параметры
    $purchases = $order->get('content') ? unserialize($order->get('content')) : array();
    $addit_params = $order->get('addit') ? unserialize($order->get('addit')) : array();
    $allowed = $order->get('allowed');
    $p_allowed = $SHKmanager->allowedArray($allowed,$purchases);
    
    //print_r($purchases); print_r($addit_params); exit;
    
    $contacts = $order->get('contacts');
    $date = $order->get('date');
    
    $orderData = $SHKmanager->getOrderData($purchases,$addit_params,$allowed);
    
    $chunk = $SHKmanager->getChunk('@INLINE '.$orderData);
    $orderData = $SHKmanager->parseTpl($chunk,array('orderID'=>$order_id, 'date'=>$date));
    
    //Контактные данные
    if($SHKmanager->is_serialized($contacts)){
        $contacts_fields = unserialize($contacts);
        $contacts_str = $SHKmanager->renderContactInfo($contacts_fields);
    }else{
        $contacts_fields = array();
        $contacts_str = $contacts;
    }
    
    //Массив данных для формы редактирования заказа
    $fieldsToEdit = array();
    foreach($purchases as $key => $product){
        $fieldsToEdit[] = array(
            'id' => $product['id'],
            'name' => $product['name'],
            'count' => $product['count'],
            'price' => $product['price'],
            'allowed' => in_array($key,$p_allowed),
            'params' => $addit_params[$key]
        );
    }
    
}

return $this->outputArray(array('orderData'=>$orderData,'contacts'=>$contacts_str,'fieldsToEdit'=>$fieldsToEdit));

?>