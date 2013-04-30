
SHK.panel.configuration = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: SHK.config.connector_url
        ,standardSubmit: true
        ,frame: false
        ,border: false
        ,bodyStyle:'padding:5px 5px 0'
        ,labelWidth: 250
        ,defaults: {width: 230}
        ,defaultType: 'textfield'
        ,layout: 'column'
        ,items: [
        {
            xtype: 'fieldset'
            ,columnWidth: 1
            ,padding: 10
            ,html: '<p><a href="index.php?a=system/settings">'+_('shk.common_settings')+'</a></p><br />'
            ,style: {'margin-bottom': '15px'}
        },{
            xtype: 'fieldset'
            ,title: _('shk.statuses')
            ,id: 'statusFieldset'
            //,width: 'auto'
            ,padding: 10
            ,columnWidth: 0.5
            ,collapsible: true
            ,items: this.initStatusFields()
        }
        ,{
            xtype: 'fieldset'
            ,title: _('shk.conf_delivery')
            ,id: 'deliveryFieldset'
            ,padding: 10
            ,columnWidth: 0.5
            ,collapsible: true
            ,items: this.initDeliveryFields()
        }
    ]
    
        ,buttons: [{
        text: _('save')
        ,type: 'submit'
        ,handler: function(){
            var fp = this.ownerCt.ownerCt;
            var form_data = fp.getForm().getValues();
            fp.container.mask(_('loading'),'x-mask-loading');
            Ext.Ajax.request({
                url: SHK.config.connector_url
                ,params: {action: "mgr/save_config", data: Ext.encode(form_data)}
                ,method: 'POST'
                ,success: function(response, options){
                    fp.container.unmask();

                    Ext.Ajax.request({
                        url: SHK.config.connector_url
                        ,params: {action: "mgr/get_config"}
                        ,success: function(response, options){
                            var connector_url = SHK.config.connector_url;
                            SHK.config = Ext.util.JSON.decode(response.responseText).results;
                            SHK.config.connector_url = connector_url;
                            if(Ext.getCmp('shk-grid-orders-cmp') != null) Ext.getCmp('shk-grid-orders-cmp').getStore().reload(); //Ext.getCmp('shk-grid-orders-cmp').getBottomToolbar().doRefresh(); //Ext.getCmp('shk-grid-orders-cmp').getView().refresh(true);
                        }
                        ,failure: function(frm,a) {
                            if (this.fireEvent('failure',{f:frm,a:a})) {
                                MODx.form.Handler.errorExt(a.result,frm);
                            }
                        }
                    });
                    
              }
            });
        }
    }/*,{
        text: _('cancel')
        ,handler: function(){
            this.ownerCt.ownerCt.getForm().reset();
        }
    }*/]
        
    });
    SHK.panel.configuration.superclass.constructor.call(this,config);
};
Ext.extend(SHK.panel.configuration,Ext.FormPanel,{
    
    initStatusFields: function(){
        var output = [];
        var root = this;
        for(var i = 0;i < SHK.config['statuses'].length;i++){
            var items = {
                xtype: 'compositefield'
                ,id: 'status_cf'+i
                ,hideLabel: true
                ,items: this.statusfieldItem(i,SHK.config['statuses'][i][0],SHK.config['statuses'][i][1])
            };
            output.push(items);
        }
        
        var buttons = root.addDelButtons('statusFieldset');
        output.push(buttons);
                
        return output;
    }
    
    ,statusfieldItem: function(f_index,f_name,f_color){
        var sf_items = {
            xtype: 'compositefield'
            ,items: [
                {
                    xtype: 'textfield'
                    ,id: 'status_name'+f_index
                    ,name : 'status_name[]'
                    ,width: '150'
                    ,style: {backgroundColor: f_color}
                    ,value: f_name
                },{
                    xtype: 'hidden'
                    ,id: 'status_color'+f_index
                    ,name : 'status_color[]'
                    ,value: f_color
                },{
                    xtype: 'button'
                    ,id: 'status_button'+f_index
                    ,iconCls: 'color-icn'
                    ,width: '40'
                    ,style: {position:'absolute'}
                    ,menu : {items: [
                            new Ext.ColorPalette({
                                value: f_color
                                ,index: f_index
                                ,listeners: {
                                    select: function(cp, color){
                                        //console.log(this.index);
                                        Ext.get('status_color'+this.index).set({value:'#'+color});
                                        Ext.get('status_name'+this.index).setStyle({backgroundColor:'#'+color});
                                    }
                                }
                            })
                    ]}
                }
            ]
        };
        return sf_items;
    }
    
    //кнопки добавления/удаления строк
    ,addDelButtons: function(containerId){
        var root = this;
        var buttons = {
            xtype: 'compositefield'
            ,fieldLabel: ''
            ,hideLabel: true
            ,items: [{
                    xtype: 'hidden'
                    ,name: 'hidden'
                },{
                    xtype: 'button'
                    ,iconCls: 'add-icn'
                    ,width: '30'
                    ,type: 'button'
                    ,style: {position:'absolute'}
                    ,handler: function(b,e){
                        
                        var unique = Ext.id();
                        var fieldsCount = Ext.get(Ext.query("#"+containerId+" div.x-form-item")).elements.length;
                        var lastFieldId = Ext.get(Ext.query("#"+containerId+" div.x-form-item:nth("+(fieldsCount)+")")).elements[0].id;
                        
                        Ext.DomHelper.insertBefore(lastFieldId, {tag: 'div', id: 'new-field'+unique, cls: 'x-form-item'});
                        new Ext.form.CompositeField({
                            xtype: 'compositefield'
                            ,id: 'status_cf'+unique
                            ,renderTo: 'new-field'+unique
                            ,items: containerId=='statusFieldset' ? root.statusfieldItem(unique,'','#FFFFFF') : root.deliveryfieldItem(unique,'','')
                        });
                        
                    }
                },{
                    xtype: 'button'
                    ,iconCls: 'del-icn'
                    ,width: '30'
                    ,type: 'button'
                    ,style: {position:'absolute'}
                    ,handler: function(){
                        
                        var fieldsCount = Ext.get(Ext.query("#"+containerId+" div.x-form-item")).elements.length;
                        var lastField = Ext.get(Ext.query("#"+containerId+" div.x-form-item:nth("+(fieldsCount-1)+")"));
                        if(lastField != null) lastField.remove();
                        
                    }
                }]
        };
        return buttons;
    }
    
    ,initDeliveryFields: function(){
        var output = [];
        var root = this;
        for(var i = 0;i < SHK.config['delivery'].length;i++){
            var items = {
                xtype: 'compositefield'
                ,id: 'delivery_cf'+i
                ,hideLabel: true
                ,items: this.deliveryfieldItem(i,SHK.config['delivery'][i][0],SHK.config['delivery'][i][1])
            };
            output.push(items);
        }
        
        var buttons = root.addDelButtons('deliveryFieldset');
        output.push(buttons);
        
        return output;
    }
    
    ,deliveryfieldItem: function(f_index,f_name,f_value){
        var items = {
            xtype: 'compositefield'
            ,items: [
                {
                    xtype: 'textfield'
                    ,id: 'delivery_name'+f_index
                    ,name : 'delivery_name[]'
                    ,width: '200'
                    ,style: {position:'absolute'}
                    ,value: f_name
                },{
                    xtype: 'textfield'
                    ,id: 'delivery_value'+f_index
                    ,name : 'delivery_value[]'
                    ,width: '100'
                    ,style: {position:'absolute'}
                    ,value: f_value
                }
            ]
        };
        return items;
    }
    
});
Ext.reg('shk-form-configuration',SHK.panel.configuration);
