<?php

/*

Экспорт заказов

<?xml version="1.0" encoding="utf-8"?>
<ЗаказыССайта>
  <Заказ>
    <Номер>375</Номер>
    <Дата>2012-02-16 14:11:05</Дата>
    <Статус>Подтвержден</Статус>
    <СпособДоставки>Самовывоз</СпособДоставки>
    <СпособОплаты>Наличные</СпособОплаты>
    <Контрагент>
      <Ид>2033</Ид>
      <ФИО>Фамилиев Имён Отчествович</ФИО>
      <Адрес>Московская область, г. Дубна, пр-кт Такой-то, д. 15</Адрес>
      <Email>xxxxx@xxxxx.ru</Email>
      <Телефон>x(xxx)xxx-xx-xx</Телефон>
    </Контрагент>
    <Товары>
      <Товар>
        <Ид>196</Ид>
        <Наименование>Товар</Наименование>
        <Количество>1</Количество>
        <Цена>29990</Цена>
        <СтавкаНДС>18</СтавкаНДС>
        <Сумма>29990</Сумма>
        <СуммаНДС>5398,2</СуммаНДС>
      </Товар>
    </Товары>
  </Заказ>
</ЗаказыССайта>

*/

$type = $modx->getOption('type',$scriptProperties,'xml');
$orders_ids = $modx->getOption('orders',$scriptProperties,'');
$sort = 'date';
$dir = 'DESC';
$nds_rate = 18; //НДС

function cleanDir($dir_path){
  $dir = opendir(realpath($dir_path));
  while($f = readdir($dir)){
    if(is_file($dir_path.$f)) unlink($dir_path.$f);
  }
  closedir($dir);
}

$output = array('filename'=>'');

require_once MODX_CORE_PATH."components/shopkeeper/model/shopkeeper.class.php";
require_once MODX_CORE_PATH."components/shopkeeper/model/shk_mgr.class.php";
$SHKmanager = new SHKmanager($modx);
$SHKmanager->getModConfig();
$modx->addPackage('shopkeeper', MODX_CORE_PATH.'components/shopkeeper/model/');

$c = $modx->newQuery('SHKorder');
if($orders_ids) $c->where(array('id:IN'=>explode(',',$orders_ids)));
$c->sortby($sort,$dir);
$orders = $modx->getCollection('SHKorder', $c);
$count = count($orders);
$output['count'] = $count;

if($orders){
    
    $xml = new SimpleXMLElement("<ЗаказыССайта></ЗаказыССайта>");
    
    foreach($orders as $key => $order){
        
        $purchases = $order->get('content') ? unserialize($order->get('content')) : array();
        $addit_params = $order->get('addit') ? unserialize($order->get('addit')) : array();
        $allowed = $order->get('allowed');
        $p_allowed = $SHKmanager->allowedArray($allowed,$purchases);
        
        $contacts = $order->get('contacts') ? unserialize($order->get('contacts')) : array();
        $status = isset($SHKmanager->config['statuses'][$order->get('status')]) ? $SHKmanager->config['statuses'][$order->get('status')] : array('','');
        
        //print_r($contacts); print_r($purchases); print_r($addit_params); exit;
        
        $xml_item = $xml->addChild('Заказ');
        $xml_item->addChild('Номер',$order->get('id'));
        $xml_item->addChild('Дата',$order->get('date'));
        $xml_item->addChild('Статус',$status[0]);
        $xml_item->addChild('СпособДоставки',$order->get('delivery'));
        $xml_item->addChild('СпособОплаты',$order->get('payment'));
        
        //Данные пользователя
        $userid = $order->get('userid') ? $order->get('userid') : '';
        $user_data = array();
        if($userid){
          $user = $modx->getObject('modUser',$userid);
          $profile = $user->getOne('Profile');
          $prof_ext = $profile->get('extended');
          foreach(array_merge($profile->toArray(),$prof_ext) as $k => $value){
            $user_data[$k] = $value;
          }
          unset($k,$value);
        }
        
        $xml_item_conttagent = $xml_item->addChild('Контрагент','');
        $xml_item_conttagent->addChild('Ид',$userid);
        $xml_item_conttagent->addChild('ФИО',(isset($contacts['fullname']) ? $contacts['fullname'] : ''));
        $xml_item_conttagent->addChild('Адрес',(isset($user_data['address']) ? $user_data['address'] : ''));
        $xml_item_conttagent->addChild('Email',(isset($user_data['email']) ? $user_data['email'] : ''));
        $xml_item_conttagent->addChild('Телефон',(isset($user_data['phone']) ? $user_data['phone'] : ''));
        
        $xml_item_products = $xml_item->addChild('Товары','');
        foreach($purchases as $k => $product){
          
          $price_total = $product['price'] * $product['count'];
          
          $xml_item_product = $xml_item_products->addChild('Товар');
          $xml_item_product->addChild('Ид','');
          $xml_item_product->addChild('Наименование',$product['name']);
          $xml_item_product->addChild('Количество',$product['count']);
          $xml_item_product->addChild('Цена',$product['price']);
          $xml_item_product->addChild('СтавкаНДС',$nds_rate);
          $xml_item_product->addChild('Сумма',$price_total);
          $xml_item_product->addChild('СуммаНДС',round($price_total * ($nds_rate/100),2));
          
        }
        
        //round($order->get('price') * (1-$nds_rate/100), 2)
        
    }
    
    //Создаем XML документ
    $doc = new DOMDocument('1.0','UTF-8');
    $doc->formatOutput = true;
    $domnode = dom_import_simplexml($xml);
    $domnode->preserveWhiteSpace = false;
    $domnode = $doc->importNode($domnode, true);
    $domnode = $doc->appendChild($domnode);
    $saveXml = $doc->saveXML();
    
    $dir_path = MODX_ASSETS_PATH.'files/';
    //cleanDir($dir_path);
    $filename = 'orders_'.date("d.m.y_H.i").'.xml';
    if(file_exists($dir_path.$filename)) unlink($dir_path.$filename);
    $f = fopen($dir_path.$filename,'x');
    fwrite($f,$saveXml,strlen($saveXml));
    fclose($f);
    
    $output['filepath'] = $modx->getOption('base_url').'assets/files/'.$filename;
    $output['filename'] = $filename;
    
}

return $modx->error->success('',$output);

?>