<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
$routes->get('/artikel','Artikel::index');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');
$routes->get('/user/login', 'User::login');     
$routes->post('/user/login', 'User::login');      
$routes->get('/user/logout', 'User::logout');  

// Route AJAX
$routes->get('/ajax', 'AjaxController::index');
$routes->get('/ajax/getData', 'AjaxController::getData');
$routes->get('/ajax/getById/(:num)', 'AjaxController::getById/$1');
$routes->post('/ajax/add', 'AjaxController::add');
$routes->post('/ajax/update/(:num)', 'AjaxController::update/$1');
$routes->delete('/ajax/delete/(:num)', 'AjaxController::delete/$1');
$routes->resource('post');
$routes->options('post/(:any)', 'Post::options');
$routes->post('post/(:num)/edit', 'Post::update/$1');

$routes->setAutoRoute(true);
$routes->group('admin',  ['filter' => 'auth'], function($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->add('artikel/add', 'Artikel::add');
    $routes->add('artikel/edit/(:any)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:any)', 'Artikel::delete/$1');
});