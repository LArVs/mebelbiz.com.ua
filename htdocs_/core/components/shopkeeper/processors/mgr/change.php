<?php
/**
 * @package shopkeeper
 * @subpackage processors
 */

if (empty($scriptProperties['id'])) return $modx->error->failure('Invalid data.');
if(empty($scriptProperties['status'])) $scriptProperties['status'] = 0;

$ordersIds = explode(',',$scriptProperties['id']);
$status = $scriptProperties['status'];

/* change */
foreach ($ordersIds as $item) {
    $order = $modx->getObject('SHKorder',$item);
    $order->set('status',$status);
    if ($order == null) continue;
    
    $this->modx->invokeEvent('OnSHKChangeStatus',array('order_id'=>$item,'status'=>$status));
    
    if ($order->save() === false) {
        return $modx->error->failure('error');
    }
}

return $modx->error->success('',array());