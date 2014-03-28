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

    this.init = function()
    {
        items = []
        self.getItems();
        $("#phone_order").mask("+38(999) 999-9999");
        self.checkOrder();
        self.setOnForIngridient();
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
                if (discount > 0) {
                    var newPrice = data.prices - ( (data.prices / 100) * discount );
                    $('#cost_new').html(newPrice);
                    $('#discount_in_order').show();
                    $('#form_dicount').html(' - '+discount+'%');
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
                    '<a href="#" class="del-item-order"></a>'+
                    '<span class="count-items-order">1 шт</span>'+
                    '<a href="#" class="add-item-order"></a>'+
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

        $.ajax({
            type: "POST",
            dataType: "json",
            url: '/app_dev.php/ajax/order/send_items',
            data: {data: args}
        }).success(function(data){
                console.log(data);
                if( data.error !== undefined ){
                    alert(data.error_text);
                } else {
                    self.getItems();
                    alert('Спасибо! С Вами скоро свяжутся.');
                    window.location.reload();
                }
            });

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

            $("html, body").animate({ scrollTop: 0 }, "slow");

            return false;
        });
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