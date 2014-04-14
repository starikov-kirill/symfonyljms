<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DivisionsController extends Controller
{
    public function indexAction()
    {

		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT d.id, d.name, d.fall_ball, d.status FROM LjmsGeneralBundle:Divisions d ORDER BY d.id ASC');

		$divisions = $query->getResult();
        return $this->render('LjmsGeneralBundle:Admin:divisions.html.twig', array(
        	'divisions' => $divisions
    	));        
    }
	public function editAction($id)
    {
		return $this->render('LjmsGeneralBundle:Admin:division_edit.html.twig', array ('id' => $id));  
    }
}