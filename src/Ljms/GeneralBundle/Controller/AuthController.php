<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Auth;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{
    public function indexAction(Request $request)
    {

    	 $auth = new Auth();
        $auth->setEmail('');
        $auth->setPassword('');

        $form = $this->createFormBuilder($auth)
            ->add('Email', 'text')
            ->add('Password', 'password')
            ->getForm();

        return $this->render('LjmsGeneralBundle:Admin:auth.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
