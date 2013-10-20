orderController = function()
{
    var self = this;
    var items = [];
    var monay = 0;
    var orderOn = "Оформить заказ";
    var orderOff = "Заказать пиццу";
    var url, urlGet;

    this.init = function()
    {
        self.checkOrder();
    }

    this.checkOrder = function()
    {
        var orderLink = $('#order_link');
        if (items.length > 0) {
            orderLink.html(orderOn);
        } else {
            orderLink.html(orderOff);
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
                self.animateGoToBasket(id);
            }
        });

        return false;
    }

    this.getItems = function()
    {
        var form_order = $('#order_form');
        var ar = [];

        $.ajax({
            type: "POST",
            dataType: "json",
            url: urlGet
        }).success(function(data){
            if( data.error !== undefined ){
                alert(data.error_text);
            } else {
                $.each(data.items, function(k, val){
                    ar[k] = self.createItemsHtml(val);
                });
                console.log(ar);
                $('.blockL').html(ar.join(''));
            }
        });
    }

    this.createItemsHtml = function(items)
    {
        var html = '<div class="pizzaFromBasket" data-price="25" data-id="470" data-quantity="1">'+
                    '<div class="pfb_imgHolder">'+
                        '<img src="'+items.image+'" width="100" height="100">'+
                    '</div>'+
                    '<ul>'+
                        '<li class="pfb_title"><h2>'+items.title+'</h2></li>'+
                        '<li class="pfb_size"><span>Вес, диаметр: </span><strong>'+items.weight+' гр, '+items.size+' см</strong></li>'+
                        '<li class="pfb_price" data-id="470">'+
                        '<div>'+
                            '<span class="pfb_unitSum">25 <em>грн</em></span>'+
                            '<p>1 шт</p>'+
                                '<span class="sumR"></span>'+
                                '<span class="sumL"></span>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '<div class="removeBlock"></div>'+
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
}

var order = new orderController();
$(document).ready(function(){
    order.init();
});