<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DivisionsController extends Controller
{
    public function indexAction()
    {
        return $this->render('LjmsGeneralBundle:Admin:divisions.html.twig');
    }
}