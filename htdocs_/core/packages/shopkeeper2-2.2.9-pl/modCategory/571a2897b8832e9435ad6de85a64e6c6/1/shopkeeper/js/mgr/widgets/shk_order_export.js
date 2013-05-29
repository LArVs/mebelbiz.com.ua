
/*

Кнопка экспорта заказов

OnSHKOrdersToolbarLoad

*/

if(typeof(OnSHKOrdersToolbarLoad)=='undefined'){
    var OnSHKOrdersToolbarLoad = [];
}

OnSHKOrdersToolbarLoad.push({
    
    text: _('export')
    ,handler: function(){
        
        var exp_win;
        
        if(!exp_win){
            
            var exp_pbar = new Ext.ProgressBar({
                text: 'Остановлено',
                border: false,
                boxMinHeight: 18,
                boxMaxHeight: 18,
                style: {'margin-bottom':'15px', 'height':'auto'},
                id: 'exp_pbar',
                cls:'left-align'
            });
            
            var exp_win_items = [];
            exp_win_items.push(exp_pbar);
            exp_win_items.push(
                {
                    xtype: 'radiogroup'
                    ,id: 'exp_orders_radiogroup'
                    ,fieldLabel: ''
                    ,name: 'imp_type'
                    ,autoWidth: true
                    ,columns: [100,100]
                    ,items: [{
                        checked: true
                        ,autoWidth: true
                        ,boxLabel: ' XML'
                        ,name: 'ord_exp_type'
                        ,inputValue: 'xml'
                    }]
                }
            );
            
            exp_win_items.push({html:'<div id="exportFile" style="padding:7px 0 0 0;"></div>'});
            
            exp_win = new Ext.Window({
                id: 'exp_win',
                title: 'Экспорт заказов',
                layout: 'fit',
                width: 400,
                height: 220,
                padding: '20px',
                closeAction: 'destroy',
                plain: true,
                download_path: '',
                download_file: '',
                defaults: {
                    border: false
                },
                items: exp_win_items,
                buttons: [
                //Кнопка "Скачать"
                {
                    text: 'Скачать',
                    hidden: true,
                    id: 'exp_orders_download_btn',
                    handler: function(){
                        var download_path = this.ownerCt.ownerCt.download_path;
                        var download_file = this.ownerCt.ownerCt.download_file;
                        MODx.Ajax.request({
                            url: MODx.config.connectors_url+'browser/file.php'
                            ,params: {
                                action: 'download'
                                ,file: download_path
                                ,wctx: MODx.ctx || ''
                                ,source: 0
                            }
                            ,listeners: {
                                'success':{fn:function(r) {
                                    if (!Ext.isEmpty(r.object.url)) {
                                        location.href = MODx.config.connectors_url+'browser/file.php?action=download&download=1&file='+download_path+'&HTTP_MODAUTH='+MODx.siteId+'&source=0&wctx='+MODx.ctx;
                                    }
                                },scope:this}
                            }
                        });
                    }
                },
                //Кнопка "Продолжить"
                {
                    text: 'Продолжить',
                    id: 'exp_orders_continue_btn',
                    handler: function(){
                        
                        this.disabled = true;
                        exp_pbar.wait({interval:200});
                        var root = this.ownerCt.ownerCt;
                        var ordersIds = Ext.getCmp('shk-grid-orders-cmp').getSelectedAsList();
                        if(!ordersIds) ordersIds = '';
                        
                        Ext.Ajax.request({
                            url: MODx.config.base_url+'assets/components/shopkeeper/connector.php'
                            ,params: {action: 'mgr/export_orders', orders: ordersIds, type: Ext.getCmp('exp_orders_radiogroup').getValue().getRawValue()}
                            ,method: 'POST'
                            ,success: function(response, options){
                                
                                var result = Ext.util.JSON.decode(response.responseText);
                                
                                setTimeout(function(){
                                    
                                    exp_pbar.reset();
                                    exp_pbar.updateText('Готово');
                                    Ext.getCmp('exp_orders_continue_btn').setDisabled(false);
                                    
                                    if(result.object.filename!=''){
                                        Ext.get('exportFile').update('<a class="download-icn" href="'+result.object.filepath+'" target="_blank">'+result.object.filename+'</a>');
                                        root.download_path = result.object.filepath;
                                        root.download_file = result.object.filename;
                                        Ext.getCmp('exp_orders_download_btn').show();
                                    }else{
                                        Ext.get('exportFile').update('Нет заказов для экспорта.');
                                    }
                                    
                                },1000);
                                
                            }
                        });
                        
                    }
                },
                //Кнопка "Закрыть"
                {
                    text: 'Закрыть',
                    handler: function(){
                        exp_win.destroy();
                    }
                }]
            });
            
        }
        exp_win.show(this);
        
    }
    
});

