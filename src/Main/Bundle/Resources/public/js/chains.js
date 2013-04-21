chainController = function()
{
    var self = this;
    var map;
    var openedWindow = false;


    this.init = function()
    {
        $('.open_map').click(function(){

            self.setMap($(this).parent());
        });
    }

    this.setMap = function (divParent)
    {
        var liList = divParent.find('.popup-pizzzaz li.li_branchs');
        var mapItem = divParent.find('.map');

        liList.each(function(index){
            if ( index == 0 ) {
                var mapOptions = {
                    center:new google.maps.LatLng($(this).data('lat'),$(this).data('lng')),
                    zoom:13,
                    mapTypeId:google.maps.MapTypeId.ROADMAP,
                    scrollwheel:false
                };
                map = new google.maps.Map(mapItem[0], mapOptions);
            }
            var marker = new google.maps.Marker({
                map:map,
                position:new google.maps.LatLng($(this).data('lat'),$(this).data('lng')),
                icon: new google.maps.MarkerImage(
                    '/images/marker.png',
                    new google.maps.Size(27,28)
                )
            });
            self.attachSecretMessage(marker, $(this).find('.titles > a').html(), $(this).data('id'));
        });
    }

    this.attachSecretMessage = function (marker, str, id)
    {
        var message = ["This","is","the","secret","message"];
        var infowindow = new google.maps.InfoWindow(
            {
                content: '<b>'+str+'</b>'
            });

        google.maps.event.addListener(marker, 'click', function () {
            if (openedWindow)
                openedWindow.close();
            openedWindow = infowindow;
            infowindow.open(map, marker);
            map.setCenter(marker.getPosition());
        });
        $('#li_branch_id_'+id).click(function(){
            if (openedWindow)
                openedWindow.close();
            openedWindow = infowindow;
            infowindow.open(map, marker);
            map.setCenter(marker.getPosition());
            $('#li_branch_id_'+id).addClass('list-address_in_hover');
            $('#li_branch_id_'+id).parent().find('li').removeClass('list-address_in_hover');
        });
    }


}

var chainController = new chainController();
$(document).ready(function(){
    chainController.init();
});