menuController = function()
{
    var self = this;
    var items = [];
    var $container = $('#gallery-pizza');
    var order = false;
    var orderParam = 'price';

    this.init = function()
    {
        $('#options .option-set').change(function(){
            var $this = $(this);
            var $optionSet = $this.parent().find('.option-set');

            if ($optionSet.data('option-key') == 'sortAscending') {
                order = parseInt($this.val());
            } else if ($optionSet.data('option-key') == 'sortBy') {
                orderParam = $this.val();
            }

            self.sortingItems();

            if (order) {
                items.reverse();
            }

            self.setNewList();

            return false;
        });
        self.setData();
    }

    this.setData = function()
    {
        items = [];
        $container.find('.element').each(function() {
            var data = {
                weight: $(this).data('weight'),
                sizes: $(this).data('size'),
                price: $(this).data('price'),
                title: $(this).data('title'),
                id_attr: $(this).attr('id'),
                html_item: $(this).html()
            };

            items[items.length] = data;
        });

        return false;
    }

    this.sortingItems = function ()
    {
        if (orderParam == "weight") {
            items.sort(function(a, b) {
                return a.weight - b.weight;
            });
        } else if (orderParam == "sizes") {
            items.sort(function(a, b) {
                return a.sizes - b.sizes;
            });
        } else if (orderParam == "price") {
            items.sort(function(a, b) {
                return a.price - b.price;
            });
        } else if (orderParam == "name") {
            items.sort(function(a, b) {
                if (a.title < b.title)
                    return -1;

                if (a.title > b.title)
                    return 1;

                return 0;
            });
        }
    }

    this.setNewList = function ()
    {
        $container.html('');
        $.each(items, function(k, v){
            var newBox = $('<div/>',{
                'html': v.html_item
            });
            newBox.addClass('box element');
            newBox.attr('id', v.id_attr);
            newBox.attr('data-weight', v.weight);
            newBox.attr('data-size', v.sizes);
            newBox.attr('data-price', v.price);
            newBox.attr('data-title', v.title);

            $container.append(newBox);
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
                self.checkOrder();
            }
        });
    }
}

var menu = new menuController();
$(document).ready(function(){
    menu.init();
});