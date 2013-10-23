orderController = function()
{
    var self = this;
    var items = [];
    var monay = 0;
    var orderOn = "Оформить заказ";
    var orderOff = "Заказать пиццу";
    var url, urlGet, urlRemove;

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
//                self.animateGoToBasket(id);
                self.getItems();
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
                    ar[k] = self.createItemHtml(val);
                });
                console.log(ar);
                $('.blockL').html(ar.join(''));
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
}

var order = new orderController();
$(document).ready(function(){
    order.init();
});