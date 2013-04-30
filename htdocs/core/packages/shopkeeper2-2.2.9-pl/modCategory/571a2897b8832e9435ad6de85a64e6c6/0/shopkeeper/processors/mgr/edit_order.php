<?php
/**
 * @package shopkeeper
 * @subpackage processors
 */

if (empty($scriptProperties['data'])) return $modx->error->failure('Invalid data.');
$data = $modx->fromJSON($scriptProperties['data']);
if (!is_array($data)) return $modx->error->failure('Invalid data.');

//Определяем параметры сниппета Shopkeeper
$propertySetName = $modx->getOption('shk.property_set_default',$modx->config,'default');
$snippet = $modx->getObject('modSnippet',array('name'=>'Shopkeeper'));
$properties = $snippet->getProperties();
if($propertySetName!='default' && $modx->getCount('modPropertySet',array('name'=>$propertySetName))>0){
    $propSet = $modx->getObject('modPropertySet',array('name'=>$propertySetName));
    $propSetProperties = $propSet->getProperties();
    if(is_array($propSetProperties)) $properties = array_merge($properties,$propSetProperties);
}

require_once MODX_CORE_PATH."components/shopkeeper/model/shopkeeper.class.php";
require_once MODX_CORE_PATH."components/shopkeeper/model/shk_mgr.class.php";
$SHKmanager = new SHKmanager($modx,$properties);

$order_id = $data['order_id'];
$order = $modx->getObject('SHKorder',$order_id);

$purchases = $order->get('content') ? unserialize($order->get('content')) : array();
$addit_params = $order->get('addit') ? unserialize($order->get('addit')) : array();
$allowed = array();

//print_r($data); print_r($purchases); print_r($addit_params);

$new_purchases = array();
$new_addit_params = array();

//Применяем изменения
if(!is_array($data['name[]'])){
    $data['name[]'] = array($data['name[]']);
    $data['allowed[]'] = array($data['allowed[]']);
}

foreach($data['name[]'] as $k => $prod_name){
    
    $new_purchases[$k] = $purchases[$k];
    $new_purchases[$k]['name'] = trim($prod_name);
    $new_purchases[$k]['price'] = is_array($data['price[]']) ? trim($data['price[]'][$k]) : trim($data['price[]']);
    $new_purchases[$k]['count'] = is_array($data['count[]']) ? trim($data['count[]'][$k]) : trim($data['count[]']);
    $new_addit_params[$k] = array();
    if(isset($data['allowed[]'][$k]) && $data['allowed[]'][$k]=='on') $allowed[] = $k;
    
    //Доп параметры
    if(isset($data['param_name_'.$k.'[]']) && is_array($data['param_name_'.$k.'[]'])){
            foreach($data['param_name_'.$k.'[]'] as $i => $param_name){
                if(!$param_name) continue;
                
                $param_price = $data['param_price_'.$k.'[]'][$i];
                $new_addit_params[$k][] = array($param_name,$param_price);
                
            }
    }else if(isset($data['param_name_'.$k.'[]']) && !empty($data['param_name_'.$k.'[]'])){
        
        $param_name = $data['param_name_'.$k.'[]'];
        $param_price = !empty($data['param_price_'.$k.'[]']) ? $data['param_price_'.$k.'[]'] : 0;
        $new_addit_params[$k][0] = array($param_name,$param_price);
        
    }
    
}

//Добавление товара
if(!empty($data['add_id'])){
    
    $index = count($new_purchases);
    
    $add_id = intval($data['add_id']);
    $add_count = !empty($data['add_count']) ? $data['add_count'] : 1;
    $add_price = !empty($data['add_price']) ? $data['add_price'] : '';
    $add_params = !empty($data['add_params']) ? $data['add_params'] : '';
    $new_purchases = $SHKmanager->addToOrder($new_purchases,$add_id,$add_count,$add_price);
    
    if(count($new_purchases) > count($new_addit_params)){
        //Доп.параметры товара
        $new_addit_params[] = array();
        if(!empty($add_params)){
            $add_params_arr = explode('||',$add_params);
            foreach($add_params_arr as $add_p){
                list($a_name,$a_price) = explode('==',$add_p);
                if(!isset($a_price)) $a_price = 0;
                $a_price = floatval(str_replace(',','.',$a_price));
                $new_addit_params[$index][] = array($a_name,$a_price);
            }
        }
        
        $allowed[] = $index;
    }
}

list($price_total,$items_total,$items_unique_total) = $SHKmanager->getTotal($new_purchases,$new_addit_params,$allowed);

//Сохраняем изменения
$order->set('content',serialize($new_purchases));
$order->set('addit',serialize($new_addit_params));
$order->set('price',$price_total);
$order->set('allowed',implode(',',$allowed));
$order->save();

return $modx->error->success('');