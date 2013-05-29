<?php
/**
 * @package shopkeeper
 * @subpackage processors
 */

/* parse JSON */
if (empty($scriptProperties['data'])) return $modx->error->failure('Invalid data.');
$_DATA = $modx->fromJSON($scriptProperties['data']);
if (!is_array($_DATA)) return $modx->error->failure('Invalid data.');

/* get obj */
if (empty($_DATA['id'])) return $modx->error->failure('error');
$order_id = $_DATA['id'];
$order = $modx->getObject('SHKorder',$order_id);
if (empty($order)) return $modx->error->failure('error');
unset($_DATA['id'],$_DATA['contacts']);

//если меняется статус заказа
if(isset($_DATA['status']) && $_DATA['status']!=$order->get('status')){
    $status = $_DATA['status'];
    $modx->invokeEvent('OnSHKChangeStatus',array('order_id'=>$order_id,'status'=>$status));
    
    require_once MODX_CORE_PATH."components/shopkeeper/model/shopkeeper.class.php";
    require_once MODX_CORE_PATH."components/shopkeeper/model/shk_mgr.class.php";
    //require_once MODX_CORE_PATH."components/shopkeeper/include.parsetpl.php";
    $SHKmanager = new SHKmanager($modx);
    $SHKmanager->getModConfig();
    $SHKmanager->config['tplPath'] = isset($SHKmanager->config['tpl_path']) ? $SHKmanager->config['tpl_path'] :'core/components/shopkeeper/elements/chunks/ru/';
    
    //$modx->log(modX::LOG_LEVEL_ERROR, $status);
    
    $modx->getService('lexicon','modLexicon');
    $modx->lexicon->load($modx->config['manager_language'].':shopkeeper:default');
    
    //если указан шаблон для письма данного статуса, отправляем письмо
    if(!empty($SHKmanager->config['mailstatus_'.$status])){
        
        $SHKmanager->config['orderDataTpl'] = $SHKmanager->config['mailstatus_'.$status];
        $SHKmanager->config['additDataTpl'] = $SHKmanager->config['addit_data_tpl'];
        
        $order = $modx->getObject('SHKorder', $order_id);
        if (!empty($order)){
            
            $purchases = unserialize($order->get('content'));
            $addit_params = unserialize($order->get('addit'));
            $contacts = $order->get('contacts');
            $date = $order->get('date');
            $allowed = $order->get('allowed');
            
            if($SHKmanager->is_serialized($contacts)){
                $contacts_fields = unserialize($contacts);
                $contacts_str = $SHKmanager->renderContactInfo($contacts_fields);
            }else{
                $contacts_fields = array();
                $contacts_str = $contacts;
            }
            
            //OnSHKsendOrderMail
            $shk_plugin = '';
            $evtOut = $modx->invokeEvent('OnSHKsendOrderMail',array('order_id' => $order->get('id')));
            if(is_array($evtOut)) $shk_plugin = implode('',$evtOut);
            
            $chunkArray = array(
                'orderID' => $order_id,
                'date' => $date,
                'contacts' => $contacts_str,
                'status' => isset($SHKmanager->config['statuses'][$status][0]) ? $SHKmanager->config['statuses'][$status][0] : '',
                'order_changed_txt' => '',
                'order_notpossible_txt' => '',
                'note' => $order->get('note'),
                'plugin' => '',
                'shk_plugin' => $shk_plugin
            );
            
            $orderData = $SHKmanager->getOrderData($purchases, $addit_params);
            $chunk = $SHKmanager->getChunk('@INLINE '.$orderData);
            $orderData = $SHKmanager->parseTpl($chunk, $chunkArray);
            
            $email = $order->get('email');
            if(!empty($email) && !empty($contacts_fields['email'])) $email = $contacts_fields['email'];
            
            $SHKmanager->sendMail(str_replace('[[+site_name]]',$modx->config['site_name'],$modx->lexicon('shk.mail_change_status')),$orderData,$email);
            
        }
        
    }
}

$order->fromArray($_DATA);

//сохранение
if ($order->save() == false) {
    return $modx->error->failure('error');
}

return $modx->error->success('',$order);