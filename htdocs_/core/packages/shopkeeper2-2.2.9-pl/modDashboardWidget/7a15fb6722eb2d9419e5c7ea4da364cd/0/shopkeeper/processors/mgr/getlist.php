<?php
/**
 * Get a list of orders
 *
 * @package shopkeeper
 * @subpackage processors
 */

require_once MODX_CORE_PATH."components/shopkeeper/model/shopkeeper.class.php";
require_once MODX_CORE_PATH."components/shopkeeper/model/shk_mgr.class.php";
//require_once MODX_CORE_PATH."components/shopkeeper/include.parsetpl.php";
$SHKmanager = new SHKmanager($modx);
$SHKmanager->getModConfig();

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'id');
$dir = $modx->getOption('dir',$_REQUEST,'DESC');
$query = $modx->getOption('query',$_REQUEST,'');

//Если нужно фильтровать по группе, ищем всех пользователей в группе
$allowed_users = false;
if($SHKmanager->config['manager_view_limit'] && !$modx->user->isMember('Administrator')){
    $profile = $modx->user->getOne('Profile');
    $profile_ext = $profile->get('extended');
    if(isset($profile_ext['shk_viewrole'])){
        $group_name_arr = explode(',',str_replace(', ',',',$profile_ext['shk_viewrole']));
        $c = $modx->newQuery('modUserGroup');
        $c->where(array('name:IN' => $group_name_arr));
        $usergroups = $modx->getCollection('modUserGroup',$c);
        $group_ids = array();
        foreach($usergroups as $ugroup){
            array_push($group_ids,$ugroup->get('id'));
        }
        
        $c = $modx->newQuery('modUser');
        $c->select($modx->getSelectColumns('modUser','modUser'));
        $c->innerJoin('modUserGroupMember','UserGroupMembers');
        $c->where(array(
            'UserGroupMembers.user_group:IN' => $group_ids
        ));
        $users = $modx->getCollection('modUser',$c);
        if(count($users)>0){
            $allowed_users = array();
            foreach($users as $user){
                array_push($allowed_users,$user->get('id'));
            }
        }
    }
}

//Вытаскиваем из БД заказы
$c = $modx->newQuery('SHKorder');
if (!empty($query)) {
    $c->where(array(
        'contacts:LIKE' => '%'.$query.'%'
    ));
    $c->orCondition(array(
        'contacts:LIKE' => '%'.$query.'%'
    ));
}
if(is_array($allowed_users) && count($allowed_users)>0){
    $c->where(array(
        'userid:IN' => $allowed_users
    ));
}
$count = $modx->getCount('SHKorder',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$orders = $modx->getCollection('SHKorder', $c);

//Формируем массив данных заказов
$list = array();
$userIds = array();
foreach ($orders as $order) {
    $orderArray = $order->toArray();
    if(!in_array($orderArray['userid'],$userIds)){
        array_push($userIds,$orderArray['userid']);
    }
    if(isset($orderArray['contacts']) && $SHKmanager->is_serialized($orderArray['contacts'])){
        $contacts_fields = unserialize($orderArray['contacts']);
        $contacts_tpl_name = $SHKmanager->config['contact_template_small'];
        $orderArray['contacts'] = $SHKmanager->renderContactInfo($contacts_fields,$contacts_tpl_name);
    }
    $list[]= $orderArray;
}

unset($c);

//Получаем список имен пользователей
$webusers = array();
if(count($userIds)>0){
    $c = $modx->newQuery('modUser');
    $c->where(array('id:IN' => $userIds));
    $c->select(array('id','username'));
    $users = $modx->getCollection('modUser',$c);
    foreach ($users as $user) {
        $userArray = $user->toArray();
        $webusers[$userArray['id']] = $userArray['username'];
    }
}

foreach($list as &$val){
    if($val['userid']!=0 && in_array($val['userid'],array_keys($webusers))){
        $index = array_search($val['userid'],array_keys($webusers));
        $val['shk_username'] = current(array_slice(array_values($webusers),$index,1));
    }else{
        $val['shk_username'] = '';
    }
}

return $this->outputArray($list,$count);
