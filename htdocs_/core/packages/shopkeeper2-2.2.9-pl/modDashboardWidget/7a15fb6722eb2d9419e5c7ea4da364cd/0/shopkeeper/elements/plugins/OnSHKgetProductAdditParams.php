<?php

/*

Плагин добавляет товару доп. параметры

OnSHKgetProductAdditParams

*/

$output = '';

$product = $modx->getObject('modResource',$id);

$p_params = array(
    'discount' => '5',
    'type' => 'Новинка',
    'longtitle' => $product->get('longtitle'),
    'introtext' => $product->get('introtext')
);

$output = json_encode($p_params);

$modx->event->output($output);
