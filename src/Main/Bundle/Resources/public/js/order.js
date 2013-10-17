orderController = function()
{
    var self = this;
    var items = [];
    var monay = 0;
    var orderOn = "Оформить заказ";
    var orderOff = "Заказать пиццу";

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

    this.setMap = function ()
    {

    }

    this.attachSecretMessage = function ()
    {
        $.post( "example.php", function() {
            alert( "success" );
        })
        .done(function() {
            alert( "second success" );
        })
        .fail(function() {
            alert( "error" );
        })
        .always(function() {
            alert( "finished" );
        });
    }
}

var order = new orderController();
$(document).ready(function(){
    order.init();
});