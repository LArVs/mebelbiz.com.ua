<?php

/*
 plugin multicurrency
 System event: OnSHKbeforeCartLoad

<div>Валюта:</div>
<div>
    <select id="site_currency" name="curency">
        <option value="руб.">рубли</option>
        <option value="usd">USD</option>
        <option value="eur">Euro</option>
    </select>
</div>
<script type="text/javascript">
    $('#site_currency').bind('change',function(){
        var loc_href = window.location.href;
        window.location.href = loc_href + (loc_href.indexOf('?')>-1 ? '&' : '?') + 'scurr='+this.value;
    });
</script>

!!!НЕ ДОДЕЛАНО!!!

*/

if(isset($_POST['scurr'])){
    
    $currency = trim($_POST['scurr']);
    
    $purchases = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
    $addit_params = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : array();
    
    //курсы валют
    $curr_rate = $modx->getOption('curr_rate', $scriptProperties, 'usd==32||eur==42');
    
    
    
}

