
/**
 * Таблица заказов
 *
 * @class SHK.grid.orders
 * @extends Ext.grid
 * @param {Object} config An object of options.
 * @xtype shk-grid-orders
 */
SHK.grid.orders = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        id: 'shk-grid-orders'
        ,url: SHK.config.connector_url
        ,baseParams: {action: 'mgr/getlist'}
        ,save_action: 'mgr/updateFromGrid'
        ,fields: ['id','contacts','price','currency','note','date','status','userid'/*,'menu'*/]
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,sm: this.sm
        ,pageSize: parseInt(SHK.config.perpage)
        ,sortInfo: {field: 'id', direction: 'DESC'}
        ,sortBy: 'id'
        ,sortDir: 'DESC'
        ,autoExpandColumn: 'id'
        ,waitMsg: _('loading')
        ,autoFill: true
        ,autoDestroy: true
        /*,viewConfig: {
            forceFit: true
            ,getRowClass: function(record, index) {
                var c = record.get('status');
                return 'price-fall';

            }
        }*/
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 80
        },{
            header: _('shk.contacts')
            ,dataIndex: 'contacts'
            ,sortable: true
            ,width: 300
            ,xtype: 'templatecolumn'
            ,tpl: new Ext.XTemplate('<div style="white-space:normal;width:100%;">{contacts}</div>')
        },{
            header: _('shk.total_price')
            ,dataIndex: 'price'
            ,renderer: this.renderPrice
            ,sortable: true
            ,width: 150
        },{
            header: _('shk.note')
            ,dataIndex: 'note'
            ,sortable: true
            ,width: 300
            ,editor: {xtype: 'textfield'}
            ,xtype: 'templatecolumn'
            ,tpl: new Ext.XTemplate('<div style="white-space:normal;width:100%;">{note}</div>')
        },{
            header: _('shk.date_time')
            ,dataIndex: 'date'
            ,sortable: true
            ,width: 200
        },{
            header: _('shk.status')
            ,dataIndex: 'status'
            ,renderer: this.renderStatus
            ,sortable: true
            ,width: 200
            ,editor: {xtype: 'shk-combo-status'}
        },{
            header: _('shk.user')
            ,dataIndex: 'userid'
            ,sortable: true
            ,width: 220
            ,renderer: this.renderUserLink
        }]
        ,tbar: this.getTopToolbar()
//        ,listeners:{
//            rowcontextmenu: function(grid, row, e){
//                e.stopEvent();
//                this.rowMenu.showAt(e.getXY());
//            }
//        }
    });
    SHK.grid.orders.superclass.constructor.call(this,config);
};


Ext.extend(SHK.grid.orders,MODx.grid.Grid,{
    
    //Верхние кнопки и поля
    getTopToolbar: function(){
        var buttons = [{
            text: _('shk.bulk_actions')
            ,menu: this.bulkActions(this)
        }];
        
        //Кнопки плагинов
        if(typeof(OnSHKOrdersToolbarLoad)!='undefined' && OnSHKOrdersToolbarLoad.length>0){
            buttons.push('-');
            for(var i=0; i<OnSHKOrdersToolbarLoad.length;i++){
                buttons.push(OnSHKOrdersToolbarLoad[i]);
            }
        }
        
        buttons.push('->');
        buttons.push({
            xtype: 'textfield'
            ,id: 'shk-search-filter'
            ,emptyText: _('shk.search')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true;}
                        ,scope: cmp
                    });
                },scope:this}
            }
        });
        buttons.push({
            text: _('shk.find')
            ,handler: this.search//{xtype: '' ,blankValues: true}
        });
        
        return buttons;
    }
    
    //Контекстное меню
    ,getMenu: function() {
        var m = [{
            text: _('shk.order_desc')
            ,handler: this.viewOrder
        },'-',{
            text: _('shk.order_remove')
            ,handler: this.removeOrder
        }];
        this.addContextMenuItem(m);
        return true;
    }

//    rowMenu: new Ext.menu.Menu({
//        items : [{
//            text : _('shk.order_desc')
//            ,handler: this.viewOrder
////            ,listeners: {
////                click: function(menu, menuItem){
////                    Ext.getCmp('shk-grid-orders-cmp').viewOrder();
////                }
////            }
//        },'-',{
//            text : _('shk.order_remove')
//            ,handler: this.removeOrder
//        }]
//    })
    
    ,bulkActions: function(root){
        var tbar_menu_items = [];
        for(var i = 0; i < SHK.config['statuses'].length; i++){
            tbar_menu_items[i] = {};
            tbar_menu_items[i].text = _('shk.status')+' - '+SHK.config['statuses'][i][0];
            tbar_menu_items[i].index = i;
            tbar_menu_items[i].handler = function(){
                root.chStatusSelected(this.index);
            }
        }
        tbar_menu_items.push('-');
        tbar_menu_items.push({text: _('shk.remove_selected') ,handler: root.removeSelected ,scope: this});
        return tbar_menu_items;
    }

    ,renderPrice: function(v,md,rec){
        return v+' '+rec.data.currency;
    }
    
    ,renderUserLink: function(v,md,rec){
        //console.log(v,md,rec);
        return v!=0 ? '<a href="index.php?a=34&id='+v+'">'+rec.json.shk_username+'</a>' : '-';
    }
    
    ,renderStatus: function(v,md,rec){
        var statusName = typeof(SHK.config.statuses[v]) != 'undefined' ? SHK.config.statuses[v][0] : '';
        var statusColor = typeof(SHK.config.statuses[v]) != 'undefined' ? SHK.config.statuses[v][1] : '';
        return statusName ? '<div style="background-color:'+statusColor+';text-align:center;">'+statusName+'</div>' : '';
    }

    ,getSelectedAsList: function() {
        var sels = this.getSelectionModel().getSelections();
        if (sels.length <= 0) return false;
        var cs = '';
        for (var i=0;i<sels.length;i++) {
            cs += ','+sels[i].id;
        }
        if (cs[0] == ',') {
            cs = cs.substr(1);
        }
        return cs;
    }
    
    ,search: function(value) {
        if(typeof(value)!='string'){
            value = Ext.getCmp('shk-search-filter').getValue();
        }
        var s = this.getStore();
        s.baseParams.query = value;
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }

    ,chStatusSelected:  function(index) {
        var ordersIds = this.getSelectedAsList();
        if (ordersIds === false) return false;
        MODx.msg.confirm({
            title: _('shk.chstatus_selected')
            ,text: _('shk.order_chstatus_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/change'
                ,id: ordersIds
                ,status: index
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
        return true;
    }

    ,viewOrder: function(btn,e) {
        if (Ext.getCmp('shk-window-view-order-cmp'+this.menu.record.id)==null) {
            this.showOrderWindow = MODx.load({
                xtype: 'shk-window-view-order'
                ,id: 'shk-window-view-order-cmp'+this.menu.record.id
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            })
            .show();
        } else {
            Ext.getCmp('shk-window-view-order-cmp'+this.menu.record.id)
            //.setValues(this.menu.record)
            .show();
        }

    }

    ,removeOrder: function(){
        MODx.msg.confirm({
            title: _('shk.order_remove')
            ,text: _('shk.order_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,removeSelected: function(btn,e) {
        var ordersIds = this.getSelectedAsList();
        if (ordersIds === false) return false;
        MODx.msg.confirm({
            title: _('remove_selected')
            ,text: _('shk.order_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/remove'
                ,id: ordersIds
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
       return true;
    }
});
Ext.reg('shk-grid-orders',SHK.grid.orders);


/**
 * Выпадающий список статусов заказов
 *
 * @class SHK.combo.StatusSelect
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of options.
 * @xtype shk-combo-status
 */
SHK.combo.StatusSelect = function(config) {
    config = config || {};
    var statusNames = [];
    for(var i=0; i<SHK.config.statuses.length; i++){
        statusNames.push([SHK.config.statuses[i][0],i]);
    }
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: statusNames
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,listeners: {
//            'change': function(){
//                MODx.msg.confirm({
//                    title: _('shk.chstatus_selected')
//                    ,text: _('shk.order_chstatus_confirm')
//                });
//                return false;
//            }
        }
    });
    SHK.combo.StatusSelect.superclass.constructor.call(this,config);
};
Ext.extend(SHK.combo.StatusSelect,Ext.form.ComboBox);
Ext.reg('shk-combo-status',SHK.combo.StatusSelect);


/**
 * Окно с подробной информацией о заказе
 *
 * @class SHK.window.viewOrder
 * @extends Ext.window
 * @param {Object} config An object of options.
 * @xtype shk-window-view-order
 */
SHK.window.viewOrder = function(config) {
    config = config || {};
    //console.log(config.record);
    Ext.applyIf(config,{
        id: 'order'+config.record.id
        ,title: _('shk.order_desc')
        ,url: SHK.config.connector_url
        ,closeAction: 'destroy'//'close'
        ,width: 650
        ,height: 350
        ,minHeight: 350
        ,resizable: true
        ,autoScroll: true
        ,scroll: true
        //,autoHeight: false
        ,layout: 'fit'
        ,shadow: false
        ,padding: 10
        ,frame: true
        ,html: '<div style="min-height:300px;padding:5px 0;border:0;" id="orderDataWinContent'+config.record.id+'"></div>'
        /*
        ,items: {
            html: '<div style="min-height:300px;padding:5px 0;border:0;" id="orderDataWinContent'+config.record.id+'"></div>'
            ,scroll: true
            ,border: false
            ,bodyBorder: false
            //,padding: 10
        }
        */
        ,buttons: [{
                text: _('reset')
                ,id: 'reset'+config.record.id
                ,disabled:true
                ,handler: function() {
                    Ext.getCmp('order_edit'+config.record.id).getForm().reset();
                }
            },{
                text: _('save')
                ,'id': 'save'+config.record.id
                ,disabled:true
                ,handler: function() {
                    var form_data = Ext.getCmp('order_edit'+config.record.id).getForm().getFieldValues();
                    Ext.get('orderDataWinContent'+config.record.id).parent().mask(_('loading'),'x-mask-loading');
                    form_data.order_id = config.record.id;
                    
                    Ext.Ajax.request({
                        url: SHK.config.connector_url
                        ,params: {action: "mgr/edit_order", data: Ext.encode(form_data)}
                        ,method: 'POST'
                        ,success: function(response, options){
                            
                            Ext.get('orderDataWinContent'+config.record.id).update('');
                            Ext.getCmp('shk-window-view-order-cmp'+config.record.id).getOrderData(config.record);
                            Ext.getCmp('reset'+config.record.id).setDisabled(true);
                            Ext.getCmp('save'+config.record.id).setDisabled(true);
                            if(Ext.getCmp('shk-grid-orders-cmp') != null) Ext.getCmp('shk-grid-orders-cmp').getStore().reload();
                            
                        }
                    });
                    
                }
            },'-','-','-',
            {
                text: config.cancelBtnText || _('close')
                ,scope: this
                ,handler: function() { this.destroy(); }
            }
        ]
    });
    SHK.window.viewOrder.superclass.constructor.call(this,config);
    this.on('afterrender',function() {
        this.getOrderData(this.config.record);
    },this);
};


Ext.extend(SHK.window.viewOrder,MODx.Window,{
    
    //Подробности заказа
    getOrderData: function(record){
        Ext.get('orderDataWinContent'+record.id).parent().mask(_('loading'),'x-mask-loading');
        Ext.Ajax.request({
            url: SHK.config.connector_url
            ,params: {action: "mgr/get_orderdata", id: record.id}
            ,method: 'POST'
            ,success: function(response, options){
                
                var res = Ext.util.JSON.decode(response.responseText).results;
                
                var fieldsToEdit = [];
                for(var i=0;i<res.fieldsToEdit.length;i++){
                    
                    //Товар
                    var prod_data = [
                        {
                            layout: 'column'
                            ,bodyBorder: false
                            ,defaults: {
                                border: false
                                ,layout: 'form'
                            },
                            items: [{
                                columnWidth: .5
                                ,items: [{
                                    xtype: 'textfield'
                                    ,name: 'name[]'
                                    ,style: {'font-weight':'bold'}
                                    ,value: res.fieldsToEdit[i].name
                                    ,anchor:'98%'
                                }]
                            },{
                                columnWidth: .1
                                ,items: [{
                                    xtype: 'textfield'
                                    ,name: 'count[]'
                                    ,style: {'font-weight':'bold'}
                                    ,value: res.fieldsToEdit[i].count
                                    ,anchor:'95%'
                                }]
                            },{
                                columnWidth: .3
                                ,items: [{
                                    xtype: 'textfield'
                                    ,name: 'price[]'
                                    ,style: {'font-weight':'bold'}
                                    ,value: res.fieldsToEdit[i].price
                                    ,anchor:'95%'
                                }]
                            },{
                                columnWidth: .1
                                ,items: [{
                                    xtype: 'checkbox'
                                    ,id: 'p_allowed'+i
                                    ,name: 'allowed[]'
                                    ,checked: res.fieldsToEdit[i].allowed
                                    ,value: '1'
                                    //,el: {dom: {title: _('shk.product_allowed')}}
                                }]
                            }]
                        }
                    ];
                    
                    //Дополнительные параметры товара
                    if(res.fieldsToEdit[i].params.length > 0){
                        for(var ii=0;ii<res.fieldsToEdit[i].params.length;ii++){
                            var add_param = {
                                layout: 'column'
                                ,bodyBorder: false
                                ,defaults: {
                                    border: false
                                    ,layout: 'form'
                                },
                                items: [{
                                    columnWidth: .5
                                    ,items: [{
                                        xtype: 'textfield'
                                        ,name: 'param_name_'+i+'[]'
                                        ,style: {'font-style':'italic', 'padding':'1px 5px'}
                                        ,value: res.fieldsToEdit[i].params[ii][0]
                                        ,anchor:'98%'
                                    }]
                                },{
                                    columnWidth: .2
                                    ,items: [{
                                        xtype: 'textfield'
                                        ,name: 'param_price_'+i+'[]'
                                        ,style: {'font-style':'italic', 'padding':'2px 5px'}
                                        ,value: res.fieldsToEdit[i].params[ii][1]
                                        ,anchor:'95%'
                                    }]
                                }]
                            };
                            prod_data.push(add_param);
                        }
                    }
                    
                    fieldsToEdit[i] = {
                        xtype: 'container'
                        ,bodyBorder: false
                        ,style: {
                            'border-bottom': '1px solid #ddd'
                            ,'margin-bottom': '10px'
                        }
                        ,items: prod_data
                    };
                }
                
                //Вкладки окна с подробностями заказа
                var tabs = new Ext.TabPanel({
                    renderTo: 'orderDataWinContent'+record.id
                    ,autoShow: true
                    ,height: 350
                    ,plain: true
                    ,autoScroll: true
                    ,resizable: true
                    ,stateful: false
                    ,activeTab: 0
                    ,bodyStyle:'padding: 5px;'
                    //,border:false
                    //,bodyBorder:false
                    //,style: 'border-bottom:0;'
                    ,defaults: {
                        autoScroll: true
                        ,padding: 20
                        ,border: false
                    }
                    ,items: [
                        {
                            title: _('shk.order_purchases')
                            ,cls: 'shk_orderdata'
                            ,html: res.orderData
                        },{
                            title: _('shk.order_contacts')
                            ,cls: 'shk_contacts'
                            ,html: res.contacts
                        },{
                            title: _('shk.order_edit')
                            ,id: 'edit_tab'+record.id
                            ,items: [{
                                xtype: 'form'
                                ,id: 'order_edit'+record.id
                                ,border: false
                                ,frame: false
                                ,buttonAlign: 'left'
                                ,labelAlign: 'top'
                                ,items: [{
                                    xtype: 'container'
                                    ,items: fieldsToEdit
                                }
                                ,{
                                    xtype: 'fieldset'
                                    ,title: _('shk.add_product')
                                    ,collapsible: true
                                    ,autoHeight: true
                                    ,labelAlign: 'top'
                                    ,bodyStyle: 'padding: 0;'
                                    ,style: 'margin:10px 0;'
                                    ,border: false
                                    ,frame: false
                                    ,width: 550
                                    ,collapsed: true
                                    ,items: [{
                                        layout:'column'
                                        ,bodyBorder: false
                                        ,defaults: {
                                            border: false
                                            ,columnWidth: .2
                                            ,layout: 'form'
                                        }
                                        ,items: [{
                                            items: [{
                                                xtype: 'textfield'
                                                ,name: 'add_id'
                                                ,fieldLabel: 'ID'
                                                ,anchor:'95%'
                                            }]
                                        },{
                                            items: [{
                                                xtype: 'textfield'
                                                ,name: 'add_count'
                                                ,fieldLabel: _('shk.add_product_count')
                                                ,anchor:'95%'
                                            }]
                                        },{
                                            items: [{
                                                xtype: 'textfield'
                                                ,name: 'add_price'
                                                ,fieldLabel: _('shk.add_product_price')
                                                ,anchor:'95%'
                                            }]
                                        },{
                                            columnWidth: .4
                                            ,items: [{
                                                xtype: 'textfield'
                                                ,name: 'add_params'
                                                ,fieldLabel: _('shk.add_product_params')
                                                ,anchor:'95%'
                                            }]
                                        }]
                                    },{
                                        border: false
                                        ,html: _('shk.add_product_params_info')
                                    }
                                    ]
                                }]
                            }]
                            ,listeners: {
                                activate: function(){
                                    Ext.getCmp('reset'+record.id).setDisabled(false);
                                    Ext.getCmp('save'+record.id).setDisabled(false);
                                }
                                ,deactivate: function(){
                                    Ext.getCmp('reset'+record.id).setDisabled(true);
                                    Ext.getCmp('save'+record.id).setDisabled(true);
                                }
                            }
                        }
                    ]
                });
                
                Ext.get('orderDataWinContent'+record.id).parent().unmask()
                  
            }
        });
    }
});

Ext.reg('shk-window-view-order',SHK.window.viewOrder);
