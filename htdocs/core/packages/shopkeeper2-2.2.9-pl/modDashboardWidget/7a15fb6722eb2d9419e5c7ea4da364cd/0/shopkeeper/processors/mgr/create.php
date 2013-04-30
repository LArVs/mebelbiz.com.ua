<?php
/**
 * @package shopkeeper
 * @subpackage processors
 */
$order = $modx->newObject('SHKorder');
$order->fromArray($scriptProperties);

/* save */
if ($order->save() == false) {
    return $modx->error->failure('error');
}


return $modx->error->success('',$order);