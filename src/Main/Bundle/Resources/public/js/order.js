orderController = function()
{
    var self = this;

    this.init = function()
    {

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