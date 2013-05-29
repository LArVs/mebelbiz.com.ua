<?php
/* 
 * Update Shopkeeper configuration
 * 
 */

if (empty($scriptProperties['data'])) return $modx->error->failure('Invalid data.');
$config_data = $modx->fromJSON($scriptProperties['data']);
if (!is_array($config_data)) return $modx->error->failure('Invalid data.');

if(count($config_data)==0) return $modx->error->success();

//Статусы заказов
$config_data['statuses'] = array();
if(isset($config_data['status_name[]'])){
    if(!is_array($config_data['status_name[]'])) $config_data['status_name[]'] = array($config_data['status_name[]']);
    if(!is_array($config_data['status_color[]'])) $config_data['status_color[]'] = array($config_data['status_color[]']);
    
    foreach($config_data['status_name[]'] as $key => $val){
        $config_data['statuses'][] = array($val,(isset($config_data['status_color[]'][$key]) ? $config_data['status_color[]'][$key] : '#FFFF99'));
    }
    unset($config_data['status_name[]']);
    unset($config_data['status_color[]']);
}

//Доставка
$config_data['delivery'] = array();
if(isset($config_data['delivery_name[]'])){
    if(!is_array($config_data['delivery_name[]'])) $config_data['delivery_name[]'] = array($config_data['delivery_name[]']);
    if(!is_array($config_data['delivery_value[]'])) $config_data['delivery_value[]'] = array($config_data['delivery_value[]']);
    
    foreach($config_data['delivery_name[]'] as $key => $val){
        $config_data['delivery'][] = array($val,(isset($config_data['delivery_value[]'][$key]) ? $config_data['delivery_value[]'][$key] : ''));
    }
    unset($config_data['delivery_name[]']);
    unset($config_data['delivery_value[]']);
}

//echo '<pre>'; print_r($config_data); echo '</pre>';

$config_data['statuses'] = serialize($config_data['statuses']);
$config_data['delivery'] = serialize($config_data['delivery']);

foreach($config_data as $key => $val){
    $cfg = $modx->prepare("UPDATE `".$modx->config['table_prefix']."shopkeeper_config` SET value = '$val' WHERE setting = '$key'");
    $cfg->execute();
}


return $modx->error->success();

?>
