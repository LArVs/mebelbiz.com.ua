<?php

/*

Выводит заказы пользоватля

*/

@date_default_timezone_set('Europe/Moscow');
@setlocale (LC_ALL, 'ru_RU.UTF-8');

$output = '';

$on_request = $modx->getOption('on_request',$scriptProperties,'');
if($on_request && !isset($_GET[$on_request])) return '';
$usergroup = $modx->getOption('usergroup',$scriptProperties,'Покупатели');
$noResults = $modx->getOption('noResults',$scriptProperties,'Вы пока ничего у нас не купили.');
$limit = 0;
$start = 0;

$user = $modx->user;
$user_id = $modx->user->get('id');
$profile = $user->getOne('Profile');

if(!$profile || !$user->isMember($usergroup)) return $output;

require_once MODX_CORE_PATH.'components/shopkeeper/model/shopkeeper.class.php';
require_once MODX_CORE_PATH."components/shopkeeper/model/shk_mgr.class.php";
$SHKmanager = new SHKmanager($modx);
$SHKmanager->getModConfig();
$SHKmanager->config['orderDataTpl'] = $modx->getOption('orderDataTpl',$scriptProperties,'@FILE orderData.tpl');
$SHKmanager->config['additDataTpl'] = $modx->getOption('additDataTpl',$scriptProperties,'@FILE additData.tpl');

$c = $modx->newQuery('SHKorder');
$c->where(array('userid:=' => $user_id));
if($on_request && isset($_GET[$on_request]) && is_numeric($_GET[$on_request])){
    $c->where(array('id:=' => $_GET[$on_request]));
}
$count = $modx->getCount('SHKorder',$c);
$c->sortby('date','DESC');
if ($limit) $c->limit($limit,$start);
$orders = $modx->getCollection('SHKorder', $c);

//Повтор заказа
if(isset($_GET['action']) && $_GET['action']=='repeat'){
    
    $order_id = isset($_GET['id']) ? trim($_GET['id']) : 0;
    if($order_id){
        
        $order = $modx->getObject('SHKorder',array('id'=>$order_id, 'userid' => $user_id));
        if($order){
            
            $new_order = $modx->newObject('SHKorder');
            $new_order->fromArray($order->toArray());
            $new_order->set('status',0);
            $new_order->set('date',strftime('%Y-%m-%d %H:%M:%S'));
            $new_order->save();
            
        }
        $modx->sendRedirect($modx->makeUrl($modx->resource->get('id')));
        exit;
        
    }

}

//Вывод заказов
if($count>0){
    
    $index = 0;
    foreach($orders as $order){
        
        $purchases = unserialize($order->get('content'));
        $addit_params = unserialize($order->get('addit'));
        $date = $order->get('date');
        $allowed = $order->get('allowed');
        
        $orderData = $SHKmanager->getOrderData($purchases,$addit_params);
        
        $chunkArr = array(
            'index' => $index,
            'orderID' => $order->get('id'),
            'date' => $date,
            'status_id' => $order->get('status'),
            'status' => isset($SHKmanager->config['statuses'][$order->get('status')]) ? $SHKmanager->config['statuses'][$order->get('status')][0] : ''
        );
        
        $chunk_data = $SHKmanager->getChunk('@INLINE '.$orderData);
        $output .= $SHKmanager->parseTpl($chunk_data, $chunkArr);
        $index++;
        
    }

}else{
    
    $output = $noResults;
    
}

return $output;
