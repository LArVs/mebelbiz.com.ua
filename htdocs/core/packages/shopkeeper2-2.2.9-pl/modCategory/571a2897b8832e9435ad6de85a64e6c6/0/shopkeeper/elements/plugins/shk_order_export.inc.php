<?php

/*

Export Shopkeeper orders to XML

OnSHKScriptsLoad

*/

$output = '';
$eventName = $modx->event->name;

if($eventName == 'OnSHKScriptsLoad'){
    
    $output = 'assets/components/shopkeeper/js/mgr/widgets/shk_order_export.js';
    
}

$modx->event->output($output);

?>