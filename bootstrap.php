<?php

session_start();

const __ROOT__ = __DIR__;
require __ROOT__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use Bramus\Router\Router;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Illuminate\Database\Capsule\Manager as Capsule;

$env = new Dotenv();
$env->load(__ROOT__.'/.env');
$capsule = new Capsule;
$capsule->addConnection([
    "driver" => "sqlite",
    'database' => __ROOT__ . '/database/db.sqlite3',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$db = new Database();
$router = new Router();
$loader = new FilesystemLoader(__ROOT__ . '/template');
$twig = new Environment($loader, [
    'debug' => true,
    'cache' => __ROOT__ . '/template/cache'
]);
$twig->addGlobal('session', $_SESSION);
$twig->addExtension(new DebugExtension());
