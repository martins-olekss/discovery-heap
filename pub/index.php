<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

use Bramus\Router\Router;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

const __ROOT__ = __DIR__ . '/..';
require __ROOT__ . '/vendor/autoload.php';

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
$router->setBasePath('/');
$router->get('/', function () use ($twig) {
    $template = $twig->load('homepage.twig');
    echo $template->render();
});

$router->set404(function () use ($twig) {
    header('HTTP/1.1 404 Not Found');
    $template = $twig->load('notFound.twig');
    echo $template->render();
});

$router->get('/about', function () use ($twig) {
    $template = $twig->load('about.twig');
    echo $template->render();
});

$router->post('/save', function () use ($twig) {
    echo $_POST['value'];
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

$router->get('/admin/dashboard', function () use ($twig) {
    $template = $twig->load('dashboard.twig');
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