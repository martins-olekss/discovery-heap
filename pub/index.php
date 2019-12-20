<?php
session_start();
require_once __DIR__ . '/../bootstrap.php';

/*
 * Homepage
 */
$router->set404('\App\Controllers\Base@notFound');
$router->setBasePath('/');
$router->get('/', '\App\Controllers\Base@homepage');
$router->get('/about', '\App\Controllers\Base@about');

/*
 * Article resource
 */
$router->get('/post', '\App\Controllers\Post@index');
$router->get('/post/create', '\App\Controllers\Post@showCreateForm');
$router->get('/post/(\d+)', '\App\Controllers\Post@view');
$router->get('/post/(\d+)/update', '\App\Controllers\Post@showUpdateForm');
$router->get('/post/(\d+)/delete', '\App\Controllers\Post@showDeleteForm');
$router->post('/post/create', '\App\Controllers\Post@create');
$router->post('/post/(\d+)/update', '\App\Controllers\Post@update');
$router->post('/post/(\d+)/delete', '\App\Controllers\Post@delete');

/*
 * Other
 */
$router->get('/admin/dashboard', '\App\Controllers\Base@adminDashboard');
// Temporary test for Eloquent model
$router->get('/registerTestUser', '\App\Controllers\User@register');
// Temporary test for Elasticsearch
$router->get('/search', '\App\Controllers\Elasticsearch@showSearch');
$router->post('/search', '\App\Controllers\Elasticsearch@handleSearch');
// Test PDF
$router->get('/pdf', '\App\Controllers\PDF@test');

/*
 * Authorisation
 */
$router->before('GET|POST', '/admin/.*', function () {
    /*
     * For now ignore login restriction middleware
     */
    //    if (!isset($_SESSION['id'])) {
    //        header('location: /user');
    //        exit();
    //    }
});

$router->get('/logout', function () {
    User::logoutUser();
    header('location: /user');
    exit;
});

$router->get('/error', function () use ($twig) {
    $message = 'An error occurred ';
    $message .= '<a href="/">go back</a>';
    echo $message;
});

$router->post('/processLogin', function () use ($db) {
    $user = new User($db);
    if ($user->loginUser(App::post())) {
        header('location: /user');
    } else {
        header('location: /');
    }
    exit;
});

$router->get('/user', function () use ($twig) {
    $template = $twig->load('user.twig');
    echo $template->render();
});

$router->post('/processRegister', function () use ($db) {
    $user = new User($db);
    if ($user->registerUser(App::post())) {
        header('location: /user');
    } else {
        header('location: /error');
    }
    exit;
});

$router->run();