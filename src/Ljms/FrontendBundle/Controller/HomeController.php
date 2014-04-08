<?php

namespace Ljms\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('LjmsFrontendBundle:Frontend:index.html.twig');
    }
}
