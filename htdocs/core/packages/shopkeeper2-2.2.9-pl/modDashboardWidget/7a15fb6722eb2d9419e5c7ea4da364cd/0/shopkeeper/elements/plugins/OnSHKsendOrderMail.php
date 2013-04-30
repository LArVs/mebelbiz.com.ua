<?php

/*

Плагин добавляет в письмо заказа доп. данные

OnSHKsendOrderMail

*/

$output = '';

if(!empty($order_id)){
    
    $output = '<p>Тут текст, сформированный плагином по событию "OnSHKsendOrderMail".</p>';
    
}

$modx->event->output($output);

?>