menuController = function()
{
    var self = this;
    var items = [];
    var $container = $('#gallery-pizza');
    var order = false;
    var orderParam = 'price';
    var urlGetItems;
    var chain_id = 0;
    var discount = 0;

    this.init = function()
    {
        setTimeout(function(){
            $container.html('');
            self.getItems();
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
            if (discount > 0) {
                $('#form_dicount').html(' - '+discount+'%');
                $('#discount_in_order').show();
            }

            for (var i=0; i<10; i++)
            {
                if (items.length < 1) {
                    break;
                }
                setTimeout(function(){
                    self.getItems();
                }, 2000);
            }
        }, 300);
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

        $.ajax({
            type: "POST",
            dataType: "json",
            url: urlGetItems,
            data: {chain_id: chain_id}
        }).success(function(data){
            if( data.error !== undefined ){
                alert(data.error_text);
            } else {
                if (data.items.records) {
                    $.each(data.items.records, function(k, val){
                        $container.append(self.createHtmlItem(val));
                    });

                    self.setData();
                }
            }
        });
    }

    this.createHtmlItem = function(item)
    {
        var html_item  = '<div class="box element" id="item_'+item.id+'" data-weight="'+item.weight+'" data-size="'+item.weight+'" data-price="'+item.price+'" data-title="'+item.title+'">'+
                            '<div class="img-holder">'+
                                '<img src="http://1001pizza.com.ua'+item.image+'" alt="'+item.title+'" id="product_img_'+item.weight+'" width="184" height="161">'+
                                '<div class="rating">'+
                                    '<p>'+
                                        '<span style="background: url(http://1001pizza.com.ua/app/skin/v2/images/weight.png) no-repeat 0 center; padding-left: 20px;" class="weight">'+item.weight+' г</span>'+
                                        '<br>'+
                                        '<span style="background: url(http://1001pizza.com.ua/app/skin/v2/images/sizeBlock.png) no-repeat 0 center; padding-left: 20px;">'+item.size+' см</span>'+
                                        '<br>'+
                                        '<span style="background: url(/images/currency_dollar2.png) no-repeat -4px center; padding-left: 20px; height: 22px; color: green; font-weight: bold;" class="price">'+item.price+' грн</span>'+
                                    '</p>'+
                                '</div>'+
                                '<div class="feature_in" style="display: none;">'+
                                    '<p>'+item.ingredients+'</p>'+
                                '</div>';
        if (discount > 0) {
            html_item += '<div class="discount_in">'+
                                '<span>Скидка '+discount+'%</span>'+
                            '</div>';
        }
            html_item +=    '</div>'+
                            '<h2><a href="#" onclick="return false">'+item.title+'</a></h2>'+
                            '<p><a class="order_link" href="#order_from" data-id="'+item.id+'">Заказать</a></p>'+
                        '</div>';

        return html_item;
    }

    this.setUrlGetItems = function(url)
    {
        urlGetItems = url;

        return false;
    }

    this.setChainId = function(id)
    {
        chain_id = id;

        return false;
    }

    this.getChainId = function()
    {
        return chain_id;
    }

    this.setDiscount = function(num)
    {
        discount = num;

        return false;
    }

    this.getDiscount = function()
    {
        return discount;
    }
}

var menu = new menuController();
$(document).ready(function(){
    menu.init();
});