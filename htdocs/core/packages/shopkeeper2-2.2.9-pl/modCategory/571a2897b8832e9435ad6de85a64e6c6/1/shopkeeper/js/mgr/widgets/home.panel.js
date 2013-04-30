
SHK.panel.Home = function(config){
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('shk')+'</h2>'//'<h2>'+SHK.config.modName+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeItem: 0
            ,hideMode: 'offsets'
            ,stateId: 'shk-home-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
            ,items: [{
                title: _('shk.orders_menagement')
                ,defaults: {autoHeight: true}
                ,items: [{
                    html: '<p>'+_('shk.orders_menagement_desc')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    html: '<div id="shk_panel1"></div>'
                    ,border: false
                    ,bodyCssClass: 'main-wrapper'
                }]
                ,listeners: {
                    activate : function(panel){
                        if(Ext.get('shk-grid-orders-cmp')==null){
                            /*
                            MODx.load({
                                xtype: 'button'
                                ,text: _('help_ex')
                                ,renderTo: 'modAB'
                                ,handler: function() {
                                    Ext.getCmp('shk-panel-home').loadHelpPane();
                                }
                            });
                            */
                            MODx.load({
                                xtype: 'shk-grid-orders'
                                ,id: 'shk-grid-orders-cmp'
                                ,renderTo: 'shk_panel1'//panel.id
                            });
                        }
                    }
                }
            }
            ,{
                title: _('shk.configuration')
                ,defaults: {autoHeight: true}
                ,items: [{
                    html: '<p>'+_('shk.configuration_desc')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    html: '<div id="shk_panel2"></div>'
                    ,border: false
                    ,bodyCssClass: 'main-wrapper'
                }]
                ,listeners: {
                    activate : function(panel){
                        if(Ext.get('shk-form-configuration-cmp')==null){
                            MODx.load({
                                xtype: 'shk-form-configuration'
                                ,id: 'shk-form-configuration-cmp'
                                ,renderTo: 'shk_panel2'
                            });
                        }
                    }
                }
            }]
        }]
    });
    SHK.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(SHK.panel.Home,MODx.Panel,{
    
    loadHelpPane: function(b) {
        var url = MODx.config.assets_url+'components/shopkeeper/help/'+MODx.config.manager_language+'/index.html';
        if (!url) { return false; }
        MODx.helpWindow = new Ext.Window({
            title: _('help')
            ,width: 850
            ,height: 500
            ,resizable: true
            ,maximizable: true
            ,modal: false
            ,layout: 'fit'
            ,html: '<iframe src="' + url + '" width="100%" height="100%" frameborder="0"></iframe>'
        });
        MODx.helpWindow.show(b);
        return true;
    }
    
});
Ext.reg('shk-panel-home',SHK.panel.Home);
