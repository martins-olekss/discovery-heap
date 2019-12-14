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
$router->get('/admin/dashboard', '\App\Controllers\Base@adminDashboard');
// Temporary yest for Eloquent model
$router->get('/registerTestUser', '\App\Controllers\User@register');

$router->post('/save', function () use ($twig) {
    echo $_POST['value']; // Bad practice - testing
});

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
});

$router->run();