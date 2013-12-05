orderController = function()
{
    var self = this;
    var items = [];
    var monay = 0;
    var orderOn = "Оформить заказ";
    var orderOff = "Заказать пиццу";
    var url, urlGet, urlRemove;
    var discount = 0;

    this.init = function()
    {
        items = []
        self.getItems();
        $("#phone_order").mask("+38(999) 999-9999");
        self.checkOrder();
        self.setOnForIngridient();
        self.setDiscount();
        console.log(discount);
    }

    this.checkOrder = function()
    {
        var orderLink = $('#basket_box .order_link');
        if (items.length > 0) {
            orderLink.html(orderOn);
            orderLink.attr('href', '#order_from');
        } else {
            orderLink.html(orderOff);
            orderLink.attr('href', 'http://pizzza.com.ua/pizzarium/menu#gallery-pizza');
        }

        return false;
    }

    this.animateGoToBasket = function (id)
    {
        var posiBasket = $('#basket_box').offset();
        var posiProductImg = $('#product_img_'+id).offset();
        var newImg = $('div#new_img_div img');

        $(newImg).animate(
            {
                "left": posiBasket.left,
                "top":posiBasket.top,
                "opacity": 0,
                "width":"20px",
                "height":"20px",
                "border-radius":"100%"
            }, 2000);

        return false;
    }

    this.addItemToBasket = function (id)
    {
        var objItem = $('#item_'+id);
        var item = {
            id: id,
            weight: objItem.data('weight'),
            size: objItem.data('size'),
            price: objItem.data('price'),
            title: objItem.data('title'),
            image: objItem.find('img').first().attr('src')
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
                items = data.items;
                $.each(data.items, function(k, val){
                    ar[k] = self.createItemHtml(val);
                });
                $('.blockL').html(ar.join(''));
                $('#cost').html(data.prices);
                $('#money').html(data.prices);
                $('#items_count').html(data.items.length);
                if (discount > 0) {
                    var newPrice = data.prices - ( (data.prices / 100) * discount );
                    $('#cost_new').html(newPrice);
                }
                self.checkOrder();
            }
        });
    }

    this.createItemHtml = function(item)
    {
        var html = '<div class="pizzaFromBasket" data-price="25" data-id="'+item.id+'" data-quantity="1" id="item_bask_'+item.id+'">'+
                        '<div class="pfb_imgHolder">'+
                            '<img src="'+item.image+'" width="100" height="100">'+
                        '</div>'+
                        '<ul>'+
                            '<li class="pfb_title"><h2>'+item.title+'</h2></li>'+
                            '<li class="pfb_size"><span>Вес, диаметр: </span><strong>'+item.weight+' гр, '+item.size+' см</strong></li>'+
                            '<li class="pfb_price" data-id="470">'+
                                '<div>'+
                                    '<span class="pfb_unitSum">'+item.price+' <em>грн</em></span>'+
                                    '<p>1 шт</p>'+
                                '</div>'+
                            '</li>'+
                        '</ul>'+
                        '<div class="removeBlock" onclick="order.removeItem('+item.id+')">delte</div>'+
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
            url: '/ajax/order/send_items',
            data: {data: args}
        }).success(function(data){
                console.log(data);
                if( data.error !== undefined ){
                    alert(data.error_text);
                } else {
                    self.getItems();
                    alert('Спасибо! С Вами скоро свяжутся.');
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

            $.fancybox({
                padding: 10,
                cyclic: false,
                overlayShow: true,
                overlayOpacity: 0.65,
                overlayColor: '#000000',
                'href' : '#order_from'
            });
        });
    }

    this.setDiscount = function()
    {
        discount = menu.getDiscount();

        return false;
    }
}

var order = new orderController();
$(document).ready(function(){
    order.init();
});