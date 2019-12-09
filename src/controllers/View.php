<?php

namespace App\Controllers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View
{

    public $template;

    public function __construct()
    {
        $loader = new FilesystemLoader(__ROOT__ . '/template');
        $this->twig = new Environment($loader, [
            'debug' => true,
            'cache' => __ROOT__ . '/template/cache'
        ]);
    }
}