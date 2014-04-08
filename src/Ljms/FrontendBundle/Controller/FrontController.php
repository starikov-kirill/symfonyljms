<?php

namespace Ljms\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        return $this->render('LjmsFrontendBundle::home.html.twig');
    }
    public function aboutAction()
    {
        return $this->render('LjmsFrontendBundle::about.html.twig');
    }
}
