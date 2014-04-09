<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        return $this->render('LjmsGeneralBundle:Frontend:home.html.twig');
    }
    public function aboutAction()
    {
        return $this->render('LjmsGeneralBundle:Frontend:about.html.twig');
    }
}
