<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    public function homepage()
    {
        return $this->render('default/homepage.html.twig');
    }
}
