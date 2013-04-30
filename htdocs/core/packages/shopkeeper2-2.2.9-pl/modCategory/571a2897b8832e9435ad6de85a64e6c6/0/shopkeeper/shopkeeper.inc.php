<?php
/**
 * Shopkeeper
 * 
 * Shopping cart for MODx Revolution
 * 
 * @category 	   snippet
 * @version 	   2.2.9
 * @license 	   http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	   @properties
 * @internal	   @modx_category Shop
 */

//ini_set('display_errors',1);
//error_reporting(E_ALL);

if(isset($hideOn) && preg_match('/(^|\s|,)'.$modx->resource->get('id').'(,|$)/',$hideOn)) return '';

$modx->placeholders['SHK_callCount'] = isset($modx->placeholders['SHK_callCount']) ? $modx->placeholders['SHK_callCount']+1 : 1;
$SHK_callCount = $modx->placeholders['SHK_callCount'];
if($SHK_callCount>1) return '';

$manager_language = $modx->config['manager_language'];
$charset = $modx->config['modx_charset'];
$isfolder = $modx->resource->get('isfolder');
$propertySetName = $modx->getOption('propertySetName',$scriptProperties,'default');
$debug = $modx->getOption('debug',$scriptProperties,false);

if(!defined('SHOPKEEPER_PATH')) define('SHOPKEEPER_PATH', MODX_CORE_PATH."components/shopkeeper/");
if(!defined('SHOPKEEPER_URL')) define('SHOPKEEPER_URL', MODX_BASE_URL."core/components/shopkeeper/");
if(!defined('SHOPKEEPER_ASSETS_PATH')) define('SHOPKEEPER_ASSETS_PATH', MODX_BASE_PATH."assets/components/shopkeeper/");
if(!defined('SHOPKEEPER_ASSETS_URL')) define('SHOPKEEPER_ASSETS_URL', MODX_BASE_URL."assets/components/shopkeeper/");

$modx->addPackage('shopkeeper',SHOPKEEPER_PATH.'model/');

//require_once SHOPKEEPER_PATH."include.parsetpl.php";
require_once SHOPKEEPER_PATH."model/shopkeeper.class.php";
$shopCart = new Shopkeeper($modx, $scriptProperties);

$output = '';

//добавление товара в корзину
if(isset($_POST['shk-id'])){
    
    $purchaseArray = $_POST;
    $shopCart -> savePurchaseData($purchaseArray);
    $modx->sendRedirect((!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $modx->makeURL($modx->resource->get('id'),'','','abs')),0);
    exit;

}elseif(isset($_GET['shk_action'])){
  
    $action = addslashes($_GET['shk_action']);
     
    switch($action){
        case "empty"://очистка корзины
            $shopCart->emptySavedData();
        break;
        case "del"://удаление товара из корзины
            $item_index = isset($_GET['n']) && is_numeric($_GET['n']) ? $_GET['n'] : '';
            $shopCart->delArrayItem($item_index);
        break;
    }
    
    $modx->sendRedirect((!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $modx->makeURL($modx->resource->get('id'),'','','abs')),0);
    exit;

//пересчет количества товаров в корзине
}elseif(isset($_POST['shk_recount'])){
  
    if(!empty($_POST['count'])){
        $shopCart->recountAll($_POST['count']);
        $modx->sendRedirect((!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $modx->makeURL($modx->resource->get('id'),'','','abs')),0);
        exit;
    }

}

//добавление стилей и скриптов в <head>, если нужно
$headHtml = "";

if(!empty($scriptProperties['style'])){
    $modx->regClientCSS(SHOPKEEPER_ASSETS_URL."css/web/".$modx->getOption('style',$scriptProperties,'default')."/style.css");
}

if(!$modx->getOption('noJavaScript',$scriptProperties,false)){
    
    if(!$modx->getOption('noJQuery',$scriptProperties,false)){
        $modx->regClientStartupScript(SHOPKEEPER_ASSETS_URL."js/web/jquery-1.8.2.min.js");
    }
    
    //$modx->regClientStartupScript(SHOPKEEPER_ASSETS_URL."js/web/jquery.livequery.js");
    $modx->regClientStartupScript(SHOPKEEPER_ASSETS_URL."js/web/lang/{$lang}.js?v=".$shopCart->version);
    $modx->regClientStartupScript(SHOPKEEPER_ASSETS_URL."js/web/shopkeeper.js?v=".$shopCart->version);
    
    $headHtml .= '
    <script type="text/javascript">';
      
    if($modx->getOption('noConflict',$scriptProperties,false)){$headHtml .= "
        jQuery.noConflict();";
    }
    
    list($price_total,$items_total,$items_unique_total) = $shopCart->getTotal();
    
    $headHtml .= "
    var site_base_url = '".$modx->config['base_url']."';
    var shkOpt = jQuery.extend(shkOptDefault,{";
    $headHtml .= "prodCont:'".$scriptProperties['prodCont']."'";
    if($modx->getOption('lang',$scriptProperties,null)!='ru') $headHtml .= ", lang:'".$scriptProperties['lang']."'";
    if($modx->getOption('currency',$scriptProperties,null)!='руб.') $headHtml .= ", currency: '".$scriptProperties['currency']."'";
    $headHtml .= ", orderFormPage:'".$modx->getOption('orderFormPage',$scriptProperties,1)."'";
    $headHtml .= ", orderFormPageUrl:'".$modx->makeUrl($modx->getOption('orderFormPage',$scriptProperties,0),'','','abs')."'";
    if($modx->getOption('counterField',$scriptProperties,null)==true) $headHtml .= ", counterField:true";
    if($modx->getOption('counterFieldCart',$scriptProperties,null)==true) $headHtml .= ", counterFieldCart:true";
    if($scriptProperties['changePrice']) $headHtml .= ", changePrice:".($scriptProperties['changePrice']=='replace' ? "'replace'" : 'true');
    if($modx->getOption('noCounter',$scriptProperties,null)==true) $headHtml .= ", noCounter:true";
    if($modx->getOption('flyToCart',$scriptProperties,null)!='helper') $headHtml .= ", flyToCart:'".$scriptProperties['flyToCart']."'";
    if($modx->getOption('style',$scriptProperties,null)!='default') $headHtml .= ", style:'".$scriptProperties['style']."'";
    if($modx->getOption('noLoader',$scriptProperties,null)==true) $headHtml .= ", noLoader:true";
    if($modx->getOption('allowFloatCount',$scriptProperties,null)==true) $headHtml .= ", allowFloatCount:true";
    if($modx->getOption('animCart',$scriptProperties,null)==false) $headHtml .= ", animCart:false";
    if($modx->getOption('goToOrderFormPage',$scriptProperties,null)!=false) $headHtml .= ", goToOrderFormPage:true";
    if($debug) $headHtml .= ", debug:true";
    $headHtml .= ", psn:'".$shopCart->encrypt($propertySetName)."'";
    
    if(!empty($scriptProperties['cartHelperTpl'])){
        $helperChunk = $shopCart->getChunk($scriptProperties['cartHelperTpl']);
        $helperChunk_arr = preg_split("/[\r\n]+/", trim($helperChunk->get('snippet')));
        $helperStr = '';
        for($i=0;$i<count($helperChunk_arr);$i++){
            $helperStr .= ($i>0 ? '+' : '')."'".str_replace("'","\'",trim($helperChunk_arr[$i]))."'\n";
        }
        $headHtml .= "\n, shkHelper:{$helperStr}";
    }
  
    $headHtml .= "});
    SHK.data = {price_total:{$price_total}, items_total:{$items_total}, items_unique_total:{$items_unique_total}, ids:[".$shopCart->getProdIds('string')."]};
    jQuery(document).bind('ready',function(){
        jQuery(shkOpt.prodCont).shopkeeper();
    });";
    
    $headHtml .= "
    </script>";
    
    $modx->regClientStartupHTMLBlock($headHtml);
    
}

//вывод корзины
$output .= $shopCart->getCartContent();

//Вывод отладочной информации
if($debug){
    $curSavedP = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
    $curSavedA = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : array();
    $output .= '<pre>'.print_r($curSavedP,true).print_r($curSavedA,true).'</pre>';
}

return $output;