<?php
/* 
 * Плагин обновляет количество товара на складе при переводе статуса заказа в "Отправлен" (или др.)
 * 
 * OnSHKChangeStatus
 */

$eventName = $modx->event->name;

if(!isset($plugin_status)) $plugin_status = 2;
if(!isset($order_id) || !isset($status) || $status!=$plugin_status) return;

$order = $modx->getObject('SHKorder',array('id'=>$order_id));

if($order){
    
    require_once MODX_CORE_PATH."components/shopkeeper/model/shopkeeper.class.php";
    require_once MODX_CORE_PATH."components/shopkeeper/model/shk_mgr.class.php";
    $SHKmanager = new SHKmanager($modx);
    $SHKmanager->getModConfig();
    
    $purchases = unserialize($order->get('content'));
    
    $SHKmanager->updateInventory($purchases);
    
}