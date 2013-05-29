<?php

/*

OnSHKgetProductPrice

*/

$output = 0;

if(!empty($_POST['myprice']) && is_numeric($_POST['myprice'])){
    
    $output = $_POST['myprice'];
    
}else{
    
    $price_tv_name = 'price';//Имя TV цены
    $p_id = isset($purchaseArray['shk-id']) && is_numeric($purchaseArray['shk-id']) ? $purchaseArray['shk-id'] : 0;
    if($p_id){
        $price_tv = $modx->getObject('modTemplateVar',array('name'=>$price_tv_name));
        $output = $price_tv->renderOutput($p_id);
    }
    
}

$modx->event->_output = '';
$modx->event->output($output);

