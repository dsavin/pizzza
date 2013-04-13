    <?php

    use Symfony\Component\Routing\RouteCollection;
    use Symfony\Component\Routing\Route;

    $collection = new RouteCollection();

    function setMoreRoute($collection, $route_name, $url, $params)
    {
        $cityUrl = '/{_city}';
        $localeUrl = '/{_locale}';
        $defaultCity = array('_city' => 'kiev');
        $defaultLocale = array('_locale' => 'ru');
        $locales = array('_locale' => 'en|ru');

        $collection->add($route_name, new Route($url, $params + $defaultCity + $defaultLocale));
        $collection->add($route_name . '_locale', new Route($localeUrl . $url, $params + $defaultCity, $locales));
        $collection->add($route_name . '_city', new Route($cityUrl . $url, $params + $defaultLocale));
        $collection->add($route_name . '_city_locale', new Route($localeUrl . $cityUrl . $url, $params, $locales));

        return $collection;
    }

    setMoreRoute($collection, '_discounts_list', '/discounts', array(
                                                                    '_controller' => 'MainBundle:Client\Discount:list',
                                                               ));

    setMoreRoute($collection, '_chain_discounts_list', '/discounts/{chain_url}', array(
                                                                                      '_controller' => 'MainBundle:Client\Chain:discounts',
                                                                                 ));

    setMoreRoute($collection, '_delivery_list', '/delivery', array(
                                                                    '_controller' => 'MainBundle:Client\Chain:deliveryList',
                                                               ));


    setMoreRoute($collection, '_branches_all', '/{chain_url}/all', array(
                                                                              '_controller' => 'MainBundle:Client\Branch:all',
                                                                         ));

    setMoreRoute($collection, '_chain__delivery_single', '/{chain_url}/delivery', array(
                                                                    '_controller' => 'MainBundle:Client\Chain:delivery',
                                                               ));

    setMoreRoute($collection, '_branch_single', '/{chain_url}/{branch_url}', array(
                                                                                  '_controller' => 'MainBundle:Client\Branch:show',
                                                                             ));

    setMoreRoute($collection, '_chain_single', '/{chain_url}', array(
                                                                    '_controller' => 'MainBundle:Client\Chain:show',
                                                               ));

    return $collection;