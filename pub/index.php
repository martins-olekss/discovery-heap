<?php
session_start();

use Bramus\Router\Router;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Illuminate\Database\Capsule\Manager as Capsule;

const __ROOT__ = __DIR__ . '/..';
require __ROOT__ . '/vendor/autoload.php';

$capsule = new Capsule;
$capsule->addConnection([
    "driver" => "sqlite",
    'database' => __ROOT__ . '/database/db.sqlite3',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

//$conf = parse_ini_file(__ROOT__ . '/config.ini', true);

$db = new Database();
$router = new Router();
$loader = new FilesystemLoader(__ROOT__ . '/template');
$twig = new Environment($loader, [
    'debug' => true,
    'cache' => __ROOT__ . '/template/cache'
]);
$twig->addExtension(new DebugExtension());

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