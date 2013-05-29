<?php

//ini_set('display_errors',1);
//error_reporting(E_ALL);

$request = $_POST;

if(!defined('MODX_CORE_PATH')) require_once '../../../config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modX();

//Определяем контекст
$prodIdArr = isset($request['shk-id']) ? explode('__',$request['shk-id']) :array(1);
$p_id = is_numeric($prodIdArr[0]) ? $prodIdArr[0] : 1;
$resource = $modx->getObject('modResource',$p_id);
$modx->initialize(($resource ? $resource->get('context_key') : 'web'));
if($resource) $modx->resource = $resource;

/*
session_write_close();
$session_name = $modx->getOption('session_name') ? $modx->getOption('session_name') : ini_get('session.name');
$session_id = !empty($_COOKIE[$session_name]) ? $_COOKIE[$session_name] : 0;
session_id($session_id);
session_start();
*/

define('SHOPKEEPER_PATH', MODX_BASE_PATH."core/components/shopkeeper/");
define('SHOPKEEPER_URL', MODX_BASE_URL."core/components/shopkeeper/");

$manager_language = $modx->config['manager_language'];
$charset = $modx->config['modx_charset'];

header("Content-Type: text/html; charset={$charset}");

//require_once SHOPKEEPER_PATH."include.parsetpl.php";
require_once "model/shopkeeper.class.php";

function shk_decrypt($str){
    global $modx;
    if(function_exists('mcrypt_decrypt')){
        $key = isset($modx->site_id) ? $modx->site_id : session_id();
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($str), MCRYPT_MODE_ECB, $iv));
    }else{
        return trim($str);
    }
}

if(isset($request['action'])){
    
    //Определяем параметры сниппета Shopkeeper
    $sys_property_set_default = $modx->getOption('shk.property_set_default',$modx->config,'default');
    $psn_encripted = $modx->getOption('psn',$request,'');
    $propertySetName = $psn_encripted ? shk_decrypt($psn_encripted) : $sys_property_set_default;//$modx->getOption('shkPropertySetName',$_SESSION,$sys_property_set_default);
    
    $snippet = $modx->getObject('modSnippet',array('name'=>'Shopkeeper'));
    $properties = $snippet->getProperties();
    if($propertySetName!='default' && $modx->getCount('modPropertySet',array('name'=>$propertySetName))>0){
        $propSet = $modx->getObject('modPropertySet',array('name'=>$propertySetName));
        $propSetProperties = $propSet->getProperties();
        if(is_array($propSetProperties)) $properties = array_merge($properties,$propSetProperties);
    }
    
    $shkconfig = array();
    $shkconfig['charset'] = $charset;
    
    $thisPage = $_SERVER['HTTP_REFERER'];
    $action = $request['action'];
    
    $shopCart = new Shopkeeper($modx,array_merge($shkconfig,$properties));
    $shopCart->config['chCachingSnippets'] = true;
    
    switch($action){
        case "fill_cart":
            $shopCart->savePurchaseData($request);
        break;
        case "empty":
            $shopCart->emptySavedData();
        break;
        case "delete":
            $shopCart->delArrayItem($request['index']);
        break;
        case "recount":
            $shopCart->recountDataArray($request['index'],$request['count'],false);
        break;
        case "recount_all":
            if(isset($request['count']) && is_array($request['count'])){
                $shopCart->recountAll($request['count']);
            }
        break;
        case "refresh_cart":
            $cart_html = '';
        break;
        case "add_from_array":
            $ids_arr = isset($request['ids']) ? explode(',',$request['ids']) : array();
            $count_arr = isset($request['count']) ? explode(',',$request['count']) : array();
            $shopCart->addProductsFromArray($ids_arr,$count_arr);
        break;
        case "precessor":
            $cart_html = '';
            $processor_path = SHOPKEEPER_PATH."processors/web/".$request['processor'].".php";
            if(!empty($request['processor']) && !is_array($request['processor']) && file_exists($processor_path)){
                $cart_html = include $processor_path;
            }
            echo $cart_html; exit;
        break;
        default:
            $cart_html = '';
        break;
    }
    
    $cart_html = $shopCart->getCartContent($thisPage);
    $cart_html = $shopCart->stripModxTags($cart_html);
    list($price_total,$items_total,$items_unique_total) = $shopCart->getTotal();
    
    
    echo json_encode(array('price_total'=>$price_total,'items_total'=>$items_total,'items_unique_total'=>$items_unique_total,'ids'=>$shopCart->getProdIds('array'),'html'=>$cart_html));
  
}



?>