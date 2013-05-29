<?php

/* 
 * Get Shopkeeper configuration
 * 
 */

$list = array();

$c = $modx->newQuery('SHKconfig');
$config = $modx->getCollection('SHKconfig', $c);

foreach ($config as $conf) {
    $confArray = $conf->toArray();
    if($confArray['setting'] == 'statuses')
        $list[$confArray['setting']] = unserialize($confArray['value']);
    else
        $list[$confArray['setting']] = $confArray['value'];
}

return $this->outputArray($list);

?>
