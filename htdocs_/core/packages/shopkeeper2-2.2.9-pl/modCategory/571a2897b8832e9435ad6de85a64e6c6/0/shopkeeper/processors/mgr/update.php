<?php

/**
 * @package shopkeeper
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure('Invalid data.');
$order = $modx->getObject('SHKorder',$scriptProperties['id']);
if (empty($order)) return $modx->error->failure('Invalid data.');

/* set fields */
$order->fromArray($scriptProperties);

/* save */
if ($order->save() == false) {
    return $modx->error->failure('Error message.');
}

return $modx->error->success('',$order);