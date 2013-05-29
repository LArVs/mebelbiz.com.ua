<?php

/*

Статистика заказов

*/

$modx->addPackage('shopkeeper', MODX_CORE_PATH.'components/shopkeeper/model/');

$modx->getService('lexicon','modLexicon');
$modx->lexicon->load($modx->config['manager_language'].':shopkeeper:widget');

$q_where = "`date` + INTERVAL ".date('j')." DAY > NOW()";

//Статистика за текущий месяц
$chunkArr = array(
    'lang' => $modx->config['manager_language'],
    'new_count' => $modx->getCount('SHKorder',array(array('status' => 0),$q_where)),
    'canceled_count' => $modx->getCount('SHKorder',array(array('status' => 4),$q_where)),
    'done_count' => $modx->getCount('SHKorder',array(array('status' => 3),$q_where)),
    'all_count' => $modx->getCount('SHKorder',array($q_where))
);

$current_month = date('n');

//Статистика по месяцам
$stat_month = array();
$sql = "
SELECT month(`date`) AS `order_month`, count(*) AS `order_count`
FROM ".$modx->getTableName('SHKorder')."
WHERE year(`date`) = ".date('Y')."
GROUP BY month(`date`)
ORDER BY month(`date`)
LIMIT 5
";
$stmt = $modx->prepare($sql);
if ($stmt && $stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $stat_month[] = array("name"=>$modx->lexicon('shk.month'.$row['order_month']),"count"=>$row['order_count']);
    }
    $stmt->closeCursor();
}

$chunkArr['stat_month'] = json_encode($stat_month);

$tpl = <<<EOT
<script type="text/javascript">
Ext.chart.Chart.CHART_URL = 'assets/ext3/resources/charts.swf';
Ext.onReady(function(){
    var store = new Ext.data.JsonStore({
        fields: ['categorytitle', 'total'],
        data: [{
            categorytitle: '[[%shk.orders_new? &topic=`widget`&namespace=`shopkeeper`&language=`[[+lang]]`]] ([[+new_count]])',
            total: [[+new_count]]
        },{
            categorytitle: '[[%shk.orders_done? &topic=`widget`&namespace=`shopkeeper`&language=`[[+lang]]`]] ([[+done_count]])',
            total: [[+done_count]]
        },{
            categorytitle: '[[%shk.orders_canceled? &topic=`widget`&namespace=`shopkeeper`&language=`[[+lang]]`]] ([[+canceled_count]])',
            total: [[+canceled_count]]
        }]
    });
    
    new Ext.Panel({
        width: 300,
        height: 250,
        title: '[[%shk.cur_month_stat? &topic=`widget`&namespace=`shopkeeper`&language=`[[+lang]]`]]',
        renderTo: 'shk_stat',
        border: false,
        items: {
            store: store,
            xtype: 'piechart',
            dataField: 'total',
            categoryField: 'categorytitle',
            series: [{
                style: {
                    colors: ["#99CCFF", "#CCFFCC", "#FF99CC"]
                }
            }],
            extraStyle:{
                legend:{
                    display: 'bottom',
                    padding: 5,
                    font:{
                        family: 'Tahoma',
                        size: 11
                    }
                }
            }
        }
    });
    
    var store2 = new Ext.data.JsonStore({
        fields:['name', 'count'],
        data: [[+stat_month]]
    });
    
    new Ext.Panel({
        title: '[[%shk.month_stat? &topic=`widget`&namespace=`shopkeeper`&language=`[[+lang]]`]]',
        renderTo: 'shk_stat2',
        width:500,
        height:250,
        layout:'fit',
        border: false,
        items: {
            xtype: 'linechart',
            store: store2,
            xField: 'name',
            yField: 'count',
            border: false,
			listeners: {
				itemclick: function(o){
                /*
					var rec = store2.getAt(o.index);
                    MODx.msg.status({
                        title: 'Item Selected',
                        message: 'You chose: '+rec.get('name'),
                        delay: 3
                    });
                */
				}
			},
            series: [{
                type: 'column',
                displayName: '',
                yField: 'count',
                style: {
                    image:'bar.gif',
                    mode: 'stretch',
                    color:0x99BBE8
                }
            },{
                type:'line',
                displayName: '',
                yField: 'count',
                style: {
                    color: 0x15428B
                }
            }]
        }
    });
    
});
</script>

<table>
    <col width="*">
    <col width="30">
    <col width="*">
    <col width="*">
    <tr>
        <td>
            <img src="/assets/components/shopkeeper/img/shk_widget_icon2.png" alt="" />
        </td>
        <td>&nbsp;</td>
        <td>
            <div id="shk_stat"></div>
        </td>
        <td>
            <div id="shk_stat2"></div>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>
            <p style="color:#000;">[[%shk.all_orders? &topic=`widget`&namespace=`shopkeeper`&language=`[[+lang]]`]]: <b>[[+all_count]]</b></p>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
EOT;

$chunk = $modx->newObject('modChunk');
$chunk->fromArray(array('name'=>"INLINE-".uniqid(),'snippet'=>$tpl));
$chunk->setCacheable(false);

$output = $chunk->process($chunkArr);

return $output;
