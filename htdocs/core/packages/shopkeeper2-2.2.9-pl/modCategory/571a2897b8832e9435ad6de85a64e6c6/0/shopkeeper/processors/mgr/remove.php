<?php
/**
 * @package shopkeeper
 * @subpackage processors
 */

if (empty($scriptProperties['id'])) return $modx->error->failure('Invalid data.');

$ordersIds = explode(',',$scriptProperties['id']);

/* remove */
foreach ($ordersIds as $item) {
    $order = $modx->getObject('SHKorder',$item);
    if ($order == null) continue;
    if ($order->remove() === false) {
        return $modx->error->failure('error');
    }
}

return $modx->error->success('');