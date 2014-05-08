<?php

namespace Ljms\GeneralBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ljms\GeneralBundle\Form\Type\DivisionType;
use Ljms\GeneralBundle\Form\Type\DivisionFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class DivisionsController extends Controller {

    /**
     * @Route("/admin/divisions/{limit}", name="divisions", defaults={"limit" = 10})
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request, $limit) 
    { 
        $em = $this->get('doctrine.orm.entity_manager');

        // get divisions list
        $divisionsList = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllDevisions();

        $form = $this->createForm(new DivisionFilterType(), $divisionsList);

        $form->handleRequest($request);

        // get filtration data
        $data = $form->getData();

        // get filtration divisions
        $divisions = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all')
        {
            // if limit = all, count the number of records in db
            $limitRows = $em->getRepository('LjmsGeneralBundle:Divisions')->getCountNumberDivisions();
        } else 
        {
            $limitRows = $limit;
        }

        // connect pagination 
        $helper     = $this->get('ljms.helper.pagination');
        $pagination = $helper-> calculateHash($divisions, $this->get('request')->query->get('page', 1),  $limitRows);

        return array(
            'form'      => $form->createView(), 
            'divisions' => $pagination, 
            'limit'     => $limit
        );        
    }

    /**
     * @Route("/admin/add_division", name="add_division")
     * @Template()
     */
	public function addAction(Request $request) 
    {           
        $division = new Divisions();

        $form = $this->createForm(new DivisionType(), $division);

        $form->handleRequest($request);

        // save if form valid
        if ($form->isValid()) 
        {   
            $em = $this->getDoctrine()->getManager();
            $em->persist($division);
            $em->flush();

            return $this->redirect($this->generateUrl('divisions'));
        } 

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/edit_division/{id}", requirements={"id" = "\d+"}, name="edit_division")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {   
        // get object from db by id
        $division = $this->getDoctrine()->getRepository('LjmsGeneralBundle:Divisions')->find($id);

        // if an object no exists
        if (!$division)
        {
            return $this->redirect($this->generateUrl('divisions'));
        }

        $form = $this->createForm(new DivisionType(), $division);

        $form->handleRequest($request);

        // check the validity of data
        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('divisions'));
        }

        return array(
            'form'     => $form->createView(), 
            'id'       => $id, 
            'division' => $division
        );
    }

    /**
     * @Route("/admin/divisions/delete_division/{id}", requirements={"id" = "\d+"}, name="delete_division")
     */      
    public function deleteAction($id)
    {    
        // get object from db by id      
        $division = $this->getDoctrine()->getRepository('LjmsGeneralBundle:Divisions')->find($id);

        // if an object no exists
        if (!$division) 
        {
            throw $this->createNotFoundException('No division found for id '.$id);
        } 

        try 
        {   
            $em = $this->getDoctrine()->getManager();
            $em->remove($division);
            $em->flush(); 

            return new Response('TRUE');

        } catch(Exception $e) 
        {
            return new Response($e);
        }        
    }        
}