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

    setMoreRoute($collection, '_branch_single', '/{chain_url}/{branch_url}', array(
                                                                                  '_controller' => 'MainBundle:Client\Branch:show',
                                                                             ));

    return $collection;