
Ext.onReady(function() {
    MODx.load({xtype: 'shk-page-home'});
});

SHK.setContentHeight = function(){
    var windowHeight = !window.innerHeight ? document.body.clientHeight : window.innerHeight;
    var contentHeight = windowHeight - Ext.get('modx-topbar').getHeight() - Ext.get('modx-navbar').getHeight();
    Ext.get("modx-mainpanel").setStyle({
        'height': contentHeight+'px',
        'overflow': 'auto'
    });
}

SHK.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'shk-panel-home'
            ,id: 'shk-panel-home'
            ,renderTo: 'shk-panel-home-div'
        }]
    });
    SHK.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(SHK.page.Home,MODx.Component);
Ext.reg('shk-page-home',SHK.page.Home);



Ext.ux.clone = function(o) {
    if(!o || 'object' !== typeof o) {
        return o;
    }
    if('function' === typeof o.clone) {
        return o.clone();
    }
    var c = '[object Array]' === Object.prototype.toString.call(o) ? [] : {};
    var p, v;
    for(p in o) {
        if(o.hasOwnProperty(p)) {
            v = o[p];
            if(v && 'object' === typeof v) {
                c[p] = Ext.ux.clone(v);
            }
            else {
                c[p] = v;
            }
        }
    }
    return c;
};

