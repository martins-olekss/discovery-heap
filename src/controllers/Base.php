<?php

namespace App\Controllers;

class Base extends View
{

    public function homepage()
    {
        $template = $this->twig->load('homepage.twig');
        echo $template->render();
    }

    public function notFound()
    {
        header('HTTP/1.1 404 Not Found');
        $template = $this->twig->load('notFound.twig');
        echo $template->render();
    }

    public function about()
    {
        $template = $this->twig->load('about.twig');
        echo $template->render();
    }

    public function adminDashboard()
    {
        $template = $this->twig->load('dashboard.twig');
        echo $template->render();
    }
}