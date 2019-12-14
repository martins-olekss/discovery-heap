<?php

const __ROOT__ = __DIR__;
require __ROOT__ . '/vendor/autoload.php';

use Bramus\Router\Router;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Illuminate\Database\Capsule\Manager as Capsule;

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
