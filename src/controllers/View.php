<?php

namespace App\Controllers;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View
{

    public $template;

    /**
     * View constructor.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(__ROOT__ . '/template');
        $this->twig = new Environment($loader, [
            'debug' => true,
            'cache' => __ROOT__ . '/template/cache'
        ]);
    }

    /**
     * @param $templateName
     * @return bool|\Twig\TemplateWrapper
     */
    public function template($templateName) {
        try {
            return $this->twig->load($templateName);
        } catch (LoaderError $e) {
            return false;
        } catch (RuntimeError $e) {
            return false;
        } catch (SyntaxError $e) {
            return false;
        }
    }
}