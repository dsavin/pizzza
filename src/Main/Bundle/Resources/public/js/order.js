orderController = function()
{
    var self = this;
    var items = [];
    var monay = 0;
    var orderOn = "Оформить заказ";
    var orderOff = "Заказать пиццу";
    var url, urlGet, urlRemove;
    var discount = 0;
    var chain_id = 0;
    var nameIn = '';
    var phoneIn = '';
    var network = '';
    var network_id = 0;

    this.init = function()
    {
        items = []
        self.getItems();
        $("#phone_order").mask("+38(999) 999-9999");
        self.checkOrder();
        self.setOnForIngridient();

        $('.show-list-edit').click(function(){
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $('.wrap-popup-menu').removeClass('open');
            } else {
                $(this).addClass('active');
                $('.wrap-popup-menu').addClass('open');
            }

            return false;
        });
    }

    this.checkOrder = function()
    {
        var orderLink = $('#basket_box .order_link');
        if (items.length > 0) {
            orderLink.html(orderOn);
            orderLink.attr('href', '#order_from');
        }

        return false;
    }

    this.addItemToBasket = function (id)
    {
        self.setDiscount();
        self.setChainId();
        var objItem = $('#item_'+id);
        var item = {
            id: id,
            weight: objItem.data('weight'),
            size: objItem.data('size'),
            price: objItem.data('price'),
            title: objItem.data('title'),
            ingredients: objItem.find('div.feature_in > p').html(),
            image: objItem.find('img').first().attr('src'),
            discount: discount,
            chain_id: chain_id
        };

        $.ajax({
            type: "POST",
            dataType: "json",
            url: url,
            data: {"item": item}
        }).success(function(data){
            if( data.error !== undefined ){
                alert(data.error_text);
            } else {
                self.getItems();
            }
        });

        return false;
    }

    this.getItems = function()
    {
        var ar = [];

        $.ajax({
            type: "POST",
            dataType: "json",
            url: urlGet
        }).success(function(data){
            if( data.error !== undefined ){
                alert(data.error_text);
            } else {
                discount = data.discount;
                items = data.items;
                $.each(data.items, function(k, val){
                    ar[k] = self.createItemHtml(val);
                });
                $('.body-order').html(ar.join(''));
                $('#cost').html(data.prices);
                $('#money').html(data.prices);
                $('#items_count').html(data.items.length);
                if (data.prices > 0) {
                    $('#cost_new').html(data.prices);
                }
                self.checkOrder();
            }
        });
    }

    this.createItemHtml = function(item)
    {
        var html = '<div class="tr-wrap" data-id="'+item.id+'" data-quantity="1" id="item_bask_'+item.id+'">'+
            '<div class="td-col td-name">'+
                '<div class="name-wrap-order">'+
                    '<img src="'+item.image+'" width="104" height="104"/>'+
                    '<div class="descript-order">'+
                        '<div class="name-order">'+item.title+'</div>'+
                        '<div class="ingredients-order">'+item.ingredients+'</div>'+
                        '<div class="size-order"><span class="diametr"'+item.size+'</span></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="td-col td-number">'+
                '<span class="number-wrap-order">№'+item.id+'</span>'+
            '</div>'+
            '<div class="td-col td-count">'+
                '<div class="count-wrap">'+
//                    '<a href="#" class="del-item-order"></a>'+
                    '<span class="count-items-order">1 шт</span>'+
//                    '<a href="#" class="add-item-order"></a>'+
                '</div>'+
            '</div>'+
            '<div class="td-col td-price"><span>'+item.price+'</span> грн</div>'+
            '<div class="td-col td-del"><a href="#" class="del-order" onclick="order.removeItem('+item.id+')">Удалить</a></div>'+
        '</div>';

        return html;
    }

    this.setUrl = function(uri)
    {
        url = uri;

        return false;
    }

    this.setUrlGetItems = function(uri)
    {
        urlGet = uri;

        return false;
    }

    this.setUrlRemoveItem = function(uri)
    {
        urlRemove = uri;

        return false;
    }

    this.removeItem = function(id)
    {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: urlRemove,
            data: {"item_id": id}
        }).success(function(data){
                if( data.error !== undefined ){
                    alert(data.error_text);
                } else {
                    self.getItems();
                }
            });

        return false;
    }

    this.submitOrder = function()
    {
        var name = $('#name_order').val().trim();
        var phone = $('#phone_order').val().trim();

        if (name == '' || name.length < 3) {
            alert('Введите корректное имя');

            return false;
        }

        if (phone == '' || phone.length < 7) {
            alert('Введите корректный телефон');

            return false;
        }

        if (items.length < 1) {
            alert('Корзина пустая');

            return false;
        }

        self.sendOrderToPartners();

        return false;
    }

    this.sendOrderToPartners = function()
    {
        var itemsSend = [];
        $.each(items, function(k, val){
            itemsSend[itemsSend.length] = { id: val.id, qnt: 1};
        });

        var args = {
            name: $('#name_order').val().trim(),
            phone: $('#phone_order').val().trim(),
            i: itemsSend
        };

        var userData = {
            name: nameIn,
            phone: phoneIn,
            network: network,
            network_id: network_id
        };

        $.ajax({
            type: "POST",
            dataType: "json",
            url: $('#senditemsajax').val(),
            data: {data: args, user: userData}
        }).success(function(data){
                console.log(data);
                if( data.error !== undefined ){
                    alert(data.error_text);
                } else {
                    alert('Спасибо! С Вами скоро свяжутся.');
                    $('#popup-order-overlay').hide();
                    $('#popup-order-wrap').hide();
                    self.getItems();
//                    window.location.reload();
                }
            });

        return false;
    }

    this.getUserData = function(netw, data)
    {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: $('#usersocialca').val(),
            data: { data: data, network: netw }
        }).success(function(data){
                console.log(data);
                if( data.error !== undefined ){
//                    alert(data.error_text);
                } else {
                    if (data.data.img) {
                        $('#login_box').hide();
                        $('#data_box').show();
                        $('#data_box').find('img').first().attr('src',data.data.img);
                    }
                    if (data.data.name) {
                        nameIn = data.data.name;
                        $('#name_order').val(nameIn);
                        $('#data_box').find('div.name-user').first().html(data.data.name);
                    }
                    if (data.data.phone) {
                        phoneIn = data.data.phone;
                        $('#phone_order').val(phoneIn);
                    }
                    if (data.data.id) {
                        network_id = data.data.id;
                    }
                    network = netw;
//                    alert('Спасибо! С Вами скоро свяжутся.');
//                    window.location.reload();
                }
            });

        return false;
    }

    this.hellLogin = function()
    {
        hello( 'facebook' ).login();

        return false;
    }

    this.showIngredient = function(ele)
    {
        $(ele).find('.feature_in').show();
    }

    this.hideIngredient = function(ele)
    {
        $(ele).find('.feature_in').hide();
    }

    this.setOnForIngridient = function() {
        $('#gallery-pizza').on('mouseover','.box .img-holder',function(){
            self.showIngredient(this);
        });
        $('#gallery-pizza').on('mouseout','.box .img-holder',function(){
            self.hideIngredient(this);
        });
        $('#gallery-pizza').on('click','.order_link',function(){
            self.addItemToBasket($(this).data('id'));

            order.getItems();

            $('#popup-order-overlay').show();
            $('#popup-order-wrap').show();

            setTimeout( self.show_popup_order , 10 );

            $("html, body").animate({ scrollTop: 0 }, "slow");

            return false;
        });
    }

    this.show_popup_order =  function()
    {
        $('#popup-order-wrap').addClass('move');
        $('#wrapper').hide();
    }

    this.setDiscount = function()
    {
        discount = menu.getDiscount();

        return false;
    }

    this.setChainId = function()
    {
        chain_id = menu.getChainId();

        return false;
    }
}

var order = new orderController();
$(document).ready(function(){
    order.init();
});