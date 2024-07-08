<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class ErrorController extends AbstractController
{
    public function showAction(): Response
    {
        $header = Yaml::parseFile(__DIR__.'/../Entity/header.yaml');
        $contact = Yaml::parseFile(__DIR__.'/../Entity/contact.yaml');
        $footer = Yaml::parseFile(__DIR__.'/../Entity/footer.yaml');

        return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
            'controller_name' => 'ErrorController',
            'header' => $header,
            'contact' => $contact,
            'footer' => $footer,
        ]);
    }
}
