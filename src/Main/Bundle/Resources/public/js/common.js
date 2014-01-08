commonController = function()
{
    var self = this;
    var items = [];
    var itemsF = [];
    var t;

    this.init = function()
    {
        $('#list_second .gholder').each(function(){
            items.push({
                'html': $(this).html(),
                'name': $(this).find('.title h3 span a').html(),
                'name_en': translit2En($(this).find('.title h3 span a').html()),
                'rating': $(this).data('rating'),
                'id':   $(this).data('id'),
                'label':   $(this).find('.title h3 span a').html(),
                'value':   $(this).find('.title h3 span a').html()
            });
        });

        $( "#find" ).autocomplete({
            source: items,
            select: self.makeNewListItems,
            focus: self.makeNewListItems,
            response: self.makeNewListItems,
            change: self.makeNewListItems
        });

        itemsF = items;
    }

    this.deliverySort = function()
    {
        var typeSort = $('#short').val();
        if (typeSort == 1) {
            self.sortByRating();
        } else if (typeSort == 0) {
            self.sortByName();
        }

        self.buildNewList();
    }

    this.sortByRating = function()
    {
        items.sort(function(a, b){
            return a.rating - b.rating;
        });
    }

    this.sortByName = function()
    {
        items.sort(function(a, b) {
            return a.name == b.name ? 0 : (a.name < b.name ? 1 : -1);
        });
    }

    this.buildNewList = function()
    {
        $('#list_second').html('');
        $.each(items, function(index, value){
            var div = value.html;
            $('#list_second').prepend("<div class='gholder'>"+div+"</div>");
        });
    }

    this.makeNewListItems = function(event, ui)
    {
        var chainName = $("#find").val();

        if (ui.item) {
            chainName = ui.item.value;
        }

        items = [];

        if (chainName == "Название") {
            chainName = "";
        }

        var reg = new RegExp(".*"+chainName.toLowerCase()+".*");
        $.each(itemsF, function(index, value) {
            if (reg.test(value.name.toLowerCase()) || reg.test(value.name_en.toLowerCase())) {
                items.push(value);
            }
        });

        clearTimeout(t);
        t = setTimeout(function(){
            self.buildNewList();
        }, 1000);
    }
}

var common = new commonController();
$(document).ready(function(){
    common.init();
});