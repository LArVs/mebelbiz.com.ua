/**************************
* 
* http://modx-shopkeeper.ru/
* Shopkeeper 2.2.7 - shopping cart for MODX 2.x Revolution
* 
**************************/

if(typeof(site_base_url)=='undefined'){
    var site_base_url = jQuery('base').size()>0
    ? jQuery('base:first').attr('href')
    : window.location.protocol+'//'+window.location.host+'/';
}

var shk_timer;

//Настройки по умолчанию
var shkOptDefault = {
    'prop_name': 'default',
    'prodCont': 'div.shk-item',
    'lang': 'ru',
    'style':'default',
    'cartTpl': ['@FILE shopCart.tpl','',''],
    'flyToCart': 'helper',
    'currency': 'руб.',
    'orderFormPage': '0',
    'orderFormPageUrl': '/',
    'goToOrderFormPage': false,
    'priceTV': 'price',
    'noCounter': false,
    'changePrice': false,
    'counterField': false,
    'counterFieldCart': false,
    'tocartImage_width': 70,
    'tocartImage_height': 70,
    'noLoader': false,
    'animCart': true,
    'allowFloatCount': false,
    'debug': false,
    'psn': '',
    'shkHelper': '<div id="shk_prodHelper"><div><b id="shk_prodHelperName"></b></div>'
    +"\n"+'<div class="shs-count" id="shk_prodCount">'+langTxt['count']+' <input type="text" size="2" name="count" value="1" maxlength="3" />'
    +'</div><div><button class="shk-but" id="shk_confirmButton">'+langTxt['continue']+'</button> '
    +"\n"+'<button class="shk-but" id="shk_cancelButton">'+langTxt['cancel']+'</button></div></div>'
    +"\n"
};

jQuery.fn.setCounterToField = function(opt){
    var opt = jQuery.extend({img_path:'assets/components/shopkeeper/css/web/default/img/', wrapdiv:false, allowFloatCount:false, callback:function(){}}, opt);
    var imgpath = site_base_url+opt.img_path;
    function shk_checkKey(e){
        var key_code = e.which ? e.which : e.keyCode;
        var allowed = [8];
        if(opt.allowFloatCount) allowed.push(44,46);
        return (key_code>47&&key_code<58)||jQuery.inArray(key_code, allowed)>-1 ? true : false;
    }
    var countButs = '<img class="field-arr-up" src="'+imgpath+'arr_up.gif" width="17" height="9" alt="" />'
    + '<img class="field-arr-down" src="'+imgpath+'arr_down.gif" width="17" height="9" alt="" />'+"\n";
    
    if(opt.wrapdiv) jQuery(this).wrap('<div></div>');
    
    jQuery(this)
    .css({'height':'16px','border':'1px solid #888','vertical-align':'bottom','text-align':'center','padding':'1px 2px','font':'normal 11px Arial'})
    .after(countButs)
    .keypress(function(e){return shk_checkKey(e);});
    
    jQuery(this).each(function(){
        var field = this;
        jQuery(this).next('img').click(function(){
            var count = parseInt(jQuery(field).val());
            if((count+1)>=1) jQuery(field).val(count+1);
            opt.callback();
        })
        .css({'cursor':'pointer','margin':'0 0 11px 1px','vertical-align':'bottom'})
        .next('img').click(function(){
            var count = parseInt(jQuery(field).val());
            if((count-1)>=1) jQuery(field).val(count-1);
            opt.callback();
        })
        .css({'cursor':'pointer','margin':'0 0 1px -17px','vertical-align':'bottom'});
    });
    
    return this;
}

jQuery.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
};

jQuery.each(['prev', 'next'], function(unusedIndex, name) {
    jQuery.fn[name + 'AllElem'] = function(parentSelector,matchExpr) {
        var $all = jQuery(parentSelector).find('*').andSelf();
        $all = (name == 'prev') ? $all.slice(0, $all.index(this)).reverse() : $all.slice($all.index(this) + 1);
        if (matchExpr) $all = $all.filter(matchExpr);
        return $all;
    };
});

jQuery.fn.shopkeeper = function(){
    
    // jQuery 1.7+
    if(shkOpt.debug && parseFloat(jQuery.fn.jquery)<1.7) alert(langTxt['error_jqVersion']+jQuery.fn.jquery);
    
    //функция на событие отправки (submit) формы товара
    jQuery(document).on('submit', this.selector+' form', function(){
        SHK.toCart(this);
        return false;
    });
    //функция на событие клик по ссылке "Очистить корзину"
    jQuery(document).on('click', '#shk_butEmptyCart', function(){
        SHK.deleteItem('all',this);
        return false;
    });
    //функция на событие клик по кнопку "Удалить товар из корзины"
    jQuery(document).on('click', '#shopCart a.shk-del', function(){
        var index = jQuery(this).prevAllElem('#shopCart','a.shk-del').size();
        SHK.deleteItem(index,this);
        return false;
    });
    //функция на событие focus в поле изменения кол-ва товара в корзине
    if(!shkOpt.counterFieldCart){
        jQuery(document).on('focus', '#shopCart input.shk-count', function(){
            var index = jQuery(this).prevAllElem('#shopCart','input.shk-count').size();
            SHK.recountItem(index,this);
            return false;
        });
    //Обновление корзины при изменении числа в поле кол-ва товаров в корзине
    }else{
        jQuery(document).on('keyup', '#shopCart input.shk-count', function(){
            SHK.changeCartItemsCount();
        });
    }
    //применяется плагин setCounterToField для всех полей кол-ва товаров на странице (стрелки больш/меньше)
    if(shkOpt.counterField){
        jQuery("input[name='shk-count']",jQuery(this)).not(':hidden').setCounterToField(shkOpt);
    }
    //вызов инициализации добавления кнопок "больше/меньше" к полям кол-ва в корзине
    if(shkOpt.counterFieldCart) SHK.counterFieldCartInit();
    return this;
}

/**
 * Shopkeeper
 */
var SHK = {
    
    settings_qs: '',//'&prop_name='+shkOpt.prop_name+'&cartTpl='+shkOpt.cartTpl[0]+'&cartRowTpl='+shkOpt.cartTpl[1]+'&additDataTpl='+shkOpt.cartTpl[2]+'&currency='+shkOpt.currency+'&priceTV='+shkOpt.priceTV+'&noCounter='+shkOpt.noCounter+'&changePrice='+shkOpt.changePrice+'&orderFormPage='+shkOpt.orderFormPage+'&lang='+shkOpt.lang,
    
    data: {price_total:0, items_total:0, items_unique_total:0},
    
    number_format: function(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    },
    
    numFormat: function(n){
        return this.number_format(n, (Math.floor(n)===n ? 0 : 2), '.', ' ');
    },
    
    /**
     * Показывает блок с подтверждением действий
     */
    showHelper: function(elem,name,noCounter,func){
        if(typeof(jQuery(elem).get(0))=='undefined') return;
        if(typeof(func)=='undefined') var func = function(){};
        jQuery('#shk_prodHelper').remove();
        jQuery('body').append(shkOpt.shkHelper);
        jQuery('#shk_cancelButton').click(function(){
            jQuery('#shk_prodHelper').fadeOut(300,function(){jQuery(this).remove()});
            return false;
        });
        jQuery('#shk_confirmButton').click(function(){
            func();
            return false;
        });
        if(noCounter){
            jQuery('#shk_prodCount').remove();
        }else{
            jQuery('input:text','#shk_prodCount').setCounterToField(shkOpt);
        }
        var elHelper = jQuery('#shk_prodHelper');
        var btPos = this.getCenterPos(elHelper,elem);
        if(name){
            jQuery('#shk_prodHelperName').html(name);
        }else{
            jQuery('#shk_prodHelperName').remove();
        }
        jQuery('#shk_prodHelper').css({'top':btPos.y+'px','left':btPos.x+'px'}).fadeIn(500);
    },
    
    /**
     * Показывает прелоадер
     */
    showLoading: function(show){
        if(!shkOpt.noLoader){
            if(show==true){
                jQuery('body').append('<div id="shk_Loading"></div>');
                var loader = jQuery('#shk_Loading');
                var shopCart = jQuery('#shopCart');
                var btPos = this.getCenterPos(loader,shopCart);
                jQuery('#shk_Loading').css({'top':btPos.y+'px','left':btPos.x+'px'}).fadeIn(300);
            }else{
                jQuery('#shk_Loading').fadeOut(300,function(){
                    jQuery(this).remove();
                });
            }
        }
    },
    
    /**
     * Определение координат (позиции) элемента
     */
    getPosition: function(elem){
        var el = jQuery(elem).get(0);
        var p = {x: el.offsetLeft, y: el.offsetTop}
        while (el.offsetParent){
            el = el.offsetParent;
            p.x += el.offsetLeft;
            p.y += el.offsetTop;
            if (el != document.body && el != document.documentElement){
                p.x -= el.scrollLeft;
                p.y -= el.scrollTop;
            }
        }
        return p;
    },
    
    /**
     * Определяет позицию для блока отностительно центра другого блока
     */
    getCenterPos: function(elA,elB,Awidth,Aheight){
        if(typeof(Awidth)=='undefined') Awidth = jQuery(elA).outerWidth();
        if(typeof(Aheight)=='undefined') Aheight = jQuery(elA).outerHeight();
        posB = new Object();
        cntPos = new Object();
        posB = this.getPosition(elB);
        cntPos.x = Math.round((jQuery(elB).outerWidth()-Awidth)/2)+posB.x;
        cntPos.y = Math.round((jQuery(elB).outerHeight()-Aheight)/2)+posB.y;
        if(cntPos.x+Awidth>jQuery(window).width()){
            cntPos.x = Math.round(jQuery(window).width()-jQuery(elA).outerWidth())-2;
        }
        if(cntPos.x<0){
            cntPos.x = 2;
        }
        return cntPos;
    },
    
    /**
     * Аякс-запрос для отправки данных и получения HTML-кода обновленной корзины
     */
    ajaxRequest: function(params,refresh){
        if(typeof(refresh)=='undefined') var refresh = true;
        jQuery.ajax({
            type: "POST",
            cache: false,
            dataType: 'json',
            url: site_base_url+'assets/components/shopkeeper/connector_fe.php',
            data: params+SHK.settings_qs+'&psn='+encodeURIComponent(shkOpt.psn),//jQuery.extend({},{psn:shkOpt.psn})
            success: function(data){
                SHK.showLoading(false);
                if(typeof(data.price_total)!='undefined') SHK.data.price_total = data.price_total;
                if(typeof(data.items_total)!='undefined') SHK.data.items_total = data.items_total;
                if(typeof(data.items_unique_total)!='undefined') SHK.data.items_unique_total = data.items_unique_total;
                if(typeof(data.ids)!='undefined') SHK.data.ids = data.ids;
                if(shkOpt.goToOrderFormPage && window.location.href.indexOf(shkOpt.orderFormPageUrl)==-1 && data.items_total>0){
                    window.location.href = shkOpt.orderFormPageUrl;
                    return;
                }
                if(refresh){
                    if(window.location.href.indexOf('/'+shkOpt.orderFormPageUrl)>-1){
                        jQuery('#shk_butOrder').hide();
                    }
                    var cartHeight = jQuery('#shopCart').height();
                    if(typeof(data.html)!='undefined') jQuery('#shopCart').replaceWith(data.html);
                    if(window.location.href.indexOf('/'+shkOpt.orderFormPageUrl)>-1){
                        jQuery('#shk_butOrder').hide();
                    }
                    var cartheightNew = jQuery('#shopCart').height();
                    if(shkOpt.animCart) SHK.animCartHeight(cartHeight,cartheightNew);
                }
                if(typeof(SHKloadCartCallback)=='function') SHKloadCartCallback();
                if(shkOpt.counterFieldCart) SHK.counterFieldCartInit();
            }
            ,error: function(jqXHR, textStatus, errorThrown){
                if(typeof(console)!='undefined') console.log(textStatus+' '+errorThrown);
            }
        });
    },
    
    /**
     * Удаление товара из корзины
     */
    deleteItem: function(num,el,refresh){
        if(typeof(refresh)=='undefined') var refresh = true;
        var thisAction = function(){
            if(num!='all'){
                SHK.showLoading(true);
                var getParams = '&action=delete&index='+num;
                SHK.ajaxRequest(getParams,refresh);
            }else{
                SHK.emptyCart();
            }
            jQuery('#shk_prodHelper').fadeOut(500,function(){
                jQuery(this).remove();
            });
        }
        if(el!=null){
            this.showHelper(el,langTxt['confirm'],true,thisAction);
            jQuery('#shk_confirmButton').text(langTxt['yes']);
        }else{
            thisAction();
        }
    },
    
    /**
     * Пересчет кол-ва товара в корзине
     */
    recountItem: function(num,el){
        var thisAction = function(){
            var count = jQuery('input:text','#shk_prodCount').val();
            jQuery('#shk_prodHelper').fadeOut(500,function(){
                jQuery(this).remove();
            });
            if(typeof(SHKrecountItemCallback)=='function'){
                if(!SHKrecountItemCallback(count,el)) return;
            }
            SHK.showLoading(true);
            var getParams = '&action=recount&index='+num+'&count='+count;
            SHK.ajaxRequest(getParams);
        }
        this.showHelper(el,false,false,thisAction);
        el.blur();
        var thisCount = parseFloat(jQuery(el).val().replace(',','.'));
        jQuery('input:text','#shk_prodCount').val(thisCount);
    },
    
    /**
     * Пересчет всех товаров в корзине
     */
    recountItemAll: function(){
        var cartData = jQuery("#shopCart input[name='count[]']").serialize();
        if(typeof(SHKrecountItemCallback)=='function'){
            if(!SHKrecountItemCallback(count,el)) return;
        }
        var getParams = '&action=recount_all';
        if(cartData.length>0) getParams += '&'+cartData;
        SHK.showLoading(true);
        SHK.ajaxRequest(getParams);
    },
    
    /**
     * Инициализация добавления кнопок "больше/меньше" к полям кол-ва в корзине
     */
    counterFieldCartInit: function(){
        jQuery("#shopCart input.shk-count").setCounterToField(jQuery.extend({'callback':SHK.changeCartItemsCount}, shkOpt));
    },
    
    /**
     * Вызов функции пересчёта общей цены товаров в корзине при изменении кол-ва
     */
    changeCartItemsCount: function(){
        clearTimeout(shk_timer);
        shk_timer = setTimeout(function(){
                SHK.recountItemAll();
        },1000);
    },
    
    /**
     * Добавление товара в корзину
     */
    fillCart: function(thisForm,count,refresh){
        if(typeof(refresh)=='undefined') var refresh = true;
        var shopCart = jQuery('#shopCart');
        this.showLoading(true);
        var prodCount = typeof(count)!='undefined' && count!='' ? '&count='+count : '';
        var getParams = '&action=fill_cart'+SHK.settings_qs+prodCount;
        var formData = typeof(thisForm)=='object' ? jQuery(thisForm).serialize() : 'shk-id='+thisForm;
        if(typeof(SHKfillCartCallback)=='function') SHKfillCartCallback(thisForm);
        this.ajaxRequest(getParams+'&'+formData,refresh);
    },
    
    /**
     * Визуальный эффект перед добавлением товара в корзину
     */
    toCart: function(thisForm){
        var el = jQuery(':submit,input[type="image"]',thisForm).eq(0);//jQuery('input[type="submit"],input[type="image"],button[type="submit"]',thisForm).eq(0);
        var name = '';
        if(jQuery('input[name="shk-name"]',thisForm).size()>0){
            name = jQuery("input[name='shk-name']",thisForm).val();
        }else if(jQuery("h3",thisForm).size()>0){
            name = jQuery("h3",thisForm).text();
        }
        switch(shkOpt.flyToCart){
            ////////////////////////////////////////////
            //&flyToCart=`helper`
            case 'helper':
                var thisAction = function(){
                    if(typeof(SHKtoCartCallback)=='function'){
                        if(!SHKtoCartCallback(thisForm)) return false;
                    }
                    var count = jQuery('#shk_prodCount').is('*') && jQuery('input:text','#shk_prodCount').val().length>0 ? parseFloat(jQuery('input:text','#shk_prodCount').val().replace(',','.')) : '';
                    jQuery('#shk_prodHelper').animate({
                        top: cartPos.y+'px',
                        left: cartPos.x+'px'
                    },700).fadeOut(500,function(){
                        jQuery(this).remove();
                        SHK.fillCart(thisForm,count);
                    });
                }
                this.showHelper(el,name,shkOpt.noCounter,thisAction);
                var cartPos = this.getCenterPos(jQuery('#shk_prodHelper'),jQuery('#shopCart'));
            break;
            ////////////////////////////////////////////
            //&flyToCart=`image`
            case 'image':
                if(typeof(SHKtoCartCallback)=='function'){
                    if(!SHKtoCartCallback(thisForm)) return false;
                }
                var parent = jQuery(thisForm).parents(shkOpt.prodCont);
                var image = jQuery('img.shk-image:first',parent);
                if(jQuery(image).size()>0){
                    var cart = jQuery('#shopCart');
                    var btPos = this.getPosition(image);
                    var cartPos = this.getCenterPos(image,cart,shkOpt.tocartImage_width,shkOpt.tocartImage_height);
                    jQuery('img.shk-image:first',parent)
                    .clone(false)
                    .appendTo('body')
                    .css({'top':btPos.y+'px','position':'absolute','left':btPos.x+'px','opacity':0.75})
                    .animate({
                        top: cartPos.y+'px',
                        left: cartPos.x+'px',
                        width: shkOpt.tocartImage_width+'px',
                        height: shkOpt.tocartImage_height+'px'
                    },700).fadeOut(500,function(){
                        jQuery(this).remove();
                        SHK.fillCart(thisForm,0);
                    });
                }else{
                    this.fillCart(thisForm,0);
                }
                this.showHelper(el,langTxt['addedToCart'],true);
                jQuery('#shk_confirmButton,#shk_cancelButton').hide();
                clearTimeout(shk_timer);
                shk_timer = setTimeout(function(){
                    jQuery('#shk_prodHelper').fadeOut(500,function(){
                        jQuery('#shk_prodHelper').remove();
                    });
                },1000);
            break;
            ////////////////////////////////////////////
            //&flyToCart=`scrollimage`
            case 'scrollimage':
                if(typeof(SHKtoCartCallback)=='function'){
                    if(!SHKtoCartCallback(thisForm)) return false;
                }
                var parent = jQuery(thisForm).parents(shkOpt.prodCont);
                var image = jQuery('img.shk-image:first',parent);
                if(jQuery(image).size()>0){
                    var yScroll = $.browser.msie ? document.documentElement.scrollTop : self.pageYOffset;
                    var cart = jQuery('#shopCart');
                    var btPos = this.getPosition(image);
                    var cartPos = this.getCenterPos(image,cart,shkOpt.tocartImage_width,shkOpt.tocartImage_height);
                    var cartPosTop = cart.position().top;
                    if(yScroll < cartPosTop){
                        cartPosTop -= (jQuery(window).height()-cart.height());
                        if(cartPosTop < yScroll) cartPosTop = yScroll;
                    }
                    var baseDuration = 400;
                    var flyDuration = baseDuration + Math.round((Math.abs(btPos.y - cartPosTop)/baseDuration)*100);
                    var docBody = jQuery('html');//jQuery('html').scrollTop()>0 ? jQuery('html') : jQuery('body');
                    docBody.animate({
                        scrollTop: cartPosTop
                        },
                        flyDuration,
                        function(){
                            image
                            .clone(false)
                            .appendTo('body')
                            .css({'top':btPos.y+'px','position':'absolute','left':btPos.x+'px','opacity':0.75,'z-index':500})
                            .animate({
                                top: cartPos.y+'px',
                                left: cartPos.x+'px',
                                width: 60+'px',
                                height: 60+'px'
                            },flyDuration)
                            .fadeOut(500,function(){
                                jQuery(this).remove();
                                SHK.fillCart(thisForm,0);
                                docBody.animate({scrollTop:yScroll},flyDuration);
                            });
                        }
                    );
                }else{
                    this.fillCart(thisForm,0);
                }
                this.showHelper(el,langTxt['addedToCart'],true);
                jQuery('#shk_confirmButton,#shk_cancelButton').hide();
                clearTimeout(shk_timer);
                shk_timer = setTimeout(function(){
                    jQuery('#shk_prodHelper').fadeOut(500,function(){
                        jQuery('#shk_prodHelper').remove();
                    });
                },1000);
            break;
            ////////////////////////////////////////////
            //&flyToCart=`nofly`
            case 'nofly':
                if(typeof(SHKtoCartCallback)=='function'){
                    if(!SHKtoCartCallback(thisForm)) return false;
                }
                this.fillCart(thisForm,0);
                this.showHelper(el,langTxt['addedToCart'],true);
                jQuery('#shk_confirmButton,#shk_cancelButton').hide();
                clearTimeout(shk_timer);
                shk_timer = setTimeout(function(){
                    jQuery('#shk_prodHelper').fadeOut(500,function(){
                        jQuery('#shk_prodHelper').remove();
                    });
                },1000);
            break;
            ////////////////////////////////////////////
            default:
                this.fillCart(thisForm,0);
            break;
        }
    },
    
    /**
     * Индикация изменения параметра товара
     */
    additOpt: function(elem){
        var thisName = jQuery(elem).attr('name');
        var thisNameArr = thisName.split('__');
        jQuery('#add_'+thisNameArr[1]).remove();
        var additPriceSum = 0;
        var multiplication = new Array;
        var parent = jQuery(elem).parents('form');
        var params_elems = jQuery('select.shk_param,input.shk_param:checked,input.shk_param:hidden',parent).not('*[name="'+thisName+'"]');
        if(!jQuery(elem).is('[type="checkbox"]') || (jQuery(elem).is(':checked'))) params_elems = params_elems.add(elem);
        params_elems.each(function(i){
            var value = jQuery(this).val();
            var valArr = value.split('__');
            var price = valArr[1]!='' && !isNaN(valArr[1]) ? parseFloat(valArr[1]) : 0;
            if(valArr[1]!='' && isNaN(valArr[1]) && valArr[1].indexOf('*')==0){
                multiplication[multiplication.length] = parseFloat(valArr[1].replace('*',''));
            }
            additPriceSum += price;
        });
        if(additPriceSum!='' && !isNaN(additPriceSum) && !shkOpt.changePrice){
            jQuery('.shk-price:first',parent).after('<sup id="add_'+thisNameArr[1]+'" class="price-add">+'+additPriceSum+'</sup>');
        }else if(!isNaN(additPriceSum) && shkOpt.changePrice!=false){
            var priceTxt = jQuery('.shk-price:first',parent);
            var curPrice = jQuery(priceTxt).is(":has('span')") ? jQuery('span',priceTxt).text() : jQuery(priceTxt).text();
            var formated = curPrice.indexOf(' ')>0;
            if(shkOpt.changePrice=='replace'){
                var newPrice = additPriceSum>0 ? additPriceSum : parseFloat(curPrice.replace(/\D* /,''));
            }else{
                var newPrice = parseFloat(curPrice.replace(/\D* /,''))+additPriceSum;
                for(var i=0;i<multiplication.length;i++){
                    newPrice = newPrice*multiplication[i];
                }
            }
            if(formated){
                newPrice = this.numFormat(newPrice);
            }
            jQuery(priceTxt).empty().append('<span style="display:none;">'+curPrice+'</span>'+newPrice);
        }
    },
    
    /**
     * Добавление товаров в корзину по массиву ID
     */
    toCartFromArray: function(ids_arr,count_arr,refresh){
        if(typeof(refresh)=='undefined') var refresh = true;
        if(typeof(count_arr)=='undefined') var count_arr = [];
        if(typeof(ids_arr)!='object') return;
        if(typeof(SHKfillCartCallback)=='function') SHKfillCartCallback();
        this.ajaxRequest('&action=add_from_array&ids='+ids_arr.join(',')+'&count='+count_arr.join(','),refresh);
    },
    
    /**
     * Очищение корзины
     */
    emptyCart: function(refresh){
        if(typeof(refresh)=='undefined') var refresh = true;
        this.showLoading(true);
        if(typeof(SHKemptyCartCallback)=='function') SHKemptyCartCallback();
        this.ajaxRequest('&action=empty',refresh);//&cart_tpl='+shkOpt.cartTpl[0]
    },
    
    /**
     * Обновление корзины
     */
    refreshCart: function(loader){
        if(typeof(loader)=='undefined') loader = true;
        if(loader) this.showLoading(true);
        var getParams = '&action=refresh_cart';
        this.ajaxRequest(getParams);
    },
    
    /**
    * Применение способа доставки для обновления общей цены в корзине
    */
    selectDelivery: function(value){
        var getParams = '&action=refresh_cart&shk_delivery='+value;
        this.ajaxRequest(getParams);
    },
    
    /**
     * Анимация изменения размера корзины
     */
    animCartHeight: function(curH,newH){
        jQuery('#shopCart')
        .css({'height':curH+'px','overflow':'hidden'})
        .animate({height:newH+'px'},500,function(){
            jQuery(this).css({'overflow':'visible','height':'auto'});
        });
    }
    
    
}

jQuery(document).bind('ready',function(){
    //Если находимся на странице оформления заказа, скрываем ссылку на эту страницу
    if(window.location.href.indexOf('/'+shkOpt.orderFormPageUrl)>-1){
        jQuery('#shk_butOrder').hide();
    }
    //Инициализация дополнителных параметров
    jQuery('select.shk_param,input.shk_param:checked',shkOpt.prodCont).each(function(){
        SHK.additOpt(this);
    });
    if(typeof(SHKloadCartCallback)=='function') SHKloadCartCallback(true);
});
