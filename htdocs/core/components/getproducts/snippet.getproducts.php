<?php
/**
 * getProducts
 *
 * A general purpose Resource listing and summarization snippet for MODX 2.x.
 *
 * @author Andchir
 * @copyright Copyright 2013 http://no-ad.ru/
/

/*

[[!getPage?
&elementClass=`modSnippet`
&element=`getProducts`
&parents=`10`
&limit=`20`
&tvFilters=`{"param":"value"}`
&includeTVs=`1`
&includeTVList=`price,image,width,inventory`
&tpl=`product`
&pageFirstTpl=`<li class="control"><a [[+classes]] href="[[+href]]">Первая</a></li>`
&pageLastTpl=`<li class="control"><a [[+classes]] href="[[+href]]">Последняя</a></li>`
]]
<br class="clear" />
<ul class="pages">
[[!+page.nav]]
</ul>
[[+total]]

*/

//ini_set('display_errors',1);
//error_reporting(E_ALL);

require_once MODX_CORE_PATH.'components/getproducts/model/getproducts.class.php';
$getProducts = new getProducts($modx,$scriptProperties);

$table_prefix = $modx->config['table_prefix'];

$noResults = $modx->getOption('noResults',$scriptProperties,'');
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,'');
$returnIDs = $modx->getOption('returnIDs',$scriptProperties,false);
$totalVar = $modx->getOption('totalVar', $scriptProperties, 'total');
$debug = $modx->getOption('debug', $scriptProperties, false);

$parents_data = array();

$output = '';

//Поиск товаров по заданным параметрам
$getProducts->ids_arr = array();

$getProducts->getProductIds();

//Фильтрация
$getProducts->filterProducts();

$total = count($getProducts->ids_arr);
$modx->setPlaceholder($totalVar,$total);
if($total==0) return $noResults;
if($returnIDs) return implode(',',$getProducts->ids_arr);

//Вытаскиваем все данные товаров
$getProducts->products = array();
$getProducts->getProductsData();

//Собираем TV
$getProducts->appendTVs();

//Вытаскиваем данные от родителей, если нужно
$getProducts->appendFromParents();

//echo '<pre>'; print_r($getProducts->products); echo '</pre>';
//var_dump(($modx->getMicroTime() - $modx->startTime));

//Создаём HTML код по шаблону
$output .= $getProducts->getHTMLOutput();

//var_dump(($modx->getMicroTime() - $modx->startTime));

if($toPlaceholder){
    $modx->setPlaceholder($toPlaceholder,$output);
    $output = '';
}

return $output;