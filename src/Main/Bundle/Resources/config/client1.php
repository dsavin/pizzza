<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = array();
$routes['_page_index'] = array('url' => '/', 'params' => array('_controller' => 'MainBundle:Client\Page:index'));
$routes['_news_list'] = array('url' => '/public', 'params' => array('_controller' => 'MainBundle:Client\Publication:newsList'));
$routes['_recipe_list'] = array('url' => '/recipes', 'params' => array('_controller' => 'MainBundle:Client\Publication:recipesList'));
$routes['_news_single'] = array('url' => '/public/{url}', 'params' => array('_controller' => 'MainBundle:Client\Publication:news'));
$routes['_recipe_single'] = array('url' => '/recipes/{url}', 'params' => array('_controller' => 'MainBundle:Client\Publication:recipe'));
$routes['_discounts_list'] = array('url' => '/discounts', 'params' => array('_controller' => 'MainBundle:Client\Discount:list'));
$routes['_chain_discounts_list'] = array('url' => '/discounts/{chain_url}', 'params' => array('_controller' => 'MainBundle:Client\Chain:discounts'));
$routes['_delivery_list'] = array('url' => '/delivery', 'params' => array('_controller' => 'MainBundle:Client\Chain:deliveryList'));
$routes['_chain_list'] = array('url' => '/pizzerias', 'params' => array('_controller' => 'MainBundle:Client\Chain:all'));
$routes['_branches_all'] = array('url' => '/{chain_url}/all', 'params' => array('_controller' => 'MainBundle:Client\Branch:all'));
$routes['_chain_comments'] = array('url' => '/{chain_url}/comments', 'params' => array('_controller' => 'MainBundle:Client\Chain:comments'));
$routes['_chain__delivery_single'] = array('url' => '/{chain_url}/delivery', 'params' => array('_controller' => 'MainBundle:Client\Chain:delivery'));
$routes['_chain_single'] = array('url' => '/{chain_url}', 'params' => array('_controller' => 'MainBundle:Client\Chain:show'));
$routes['_branch_single'] = array('url' => '/{chain_url}/{branch_url}', 'params' => array('_controller' => 'MainBundle:Client\Branch:show'));

$collection = new RouteCollection();
$cityUrl = '/{_city}';
$localeUrl = '/{_locale}';
$defaultCity = array('_city' => 'kiev');
$defaultLocale = array('_locale' => 'ru');
$locales = array('_locale' => 'en|ru');
$cites = array('_city' => 'kiev|odessa');

foreach ($routes as $route_name => $route) {
    $collection->add($route_name, new Route($route['url'], $route['params'] + $defaultCity + $defaultLocale, $locales + $cites));
    $collection->add($route_name . '_locale', new Route($localeUrl . $route['url'], $route['params'] + $defaultCity + $defaultLocale, $locales + $cites));
    $collection->add($route_name . '_city', new Route($cityUrl . $route['url'], $route['params'] + $defaultCity + $defaultLocale, $locales + $cites));
    $collection->add($route_name . '_city_locale', new Route($localeUrl . $cityUrl . $route['url'], $route['params'] + $defaultCity + $defaultLocale, $locales + $cites));
}

return $collection;