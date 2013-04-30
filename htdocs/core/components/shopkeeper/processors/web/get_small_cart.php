<?php

/*



*/

$output = '';

$shopCart->config['cartTpl'] = '@FILE shopCart_small.tpl';
$shopCart->config['cartRowTpl'] = '@FILE shopCartRow.tpl';

$cart_html = $shopCart->getCartContent($thisPage);
$cart_html = $shopCart->stripModxTags($cart_html);

$output = $cart_html;

/*
list($price_total,$items_total,$items_unique_total) = $shopCart->getTotal();

return json_encode(array('price_total'=>$price_total,'items_total'=>$items_total,'items_unique_total'=>$items_unique_total,'ids'=>$shopCart->getProdIds('array'),'html'=>$cart_html));;
*/

return $output;

?>