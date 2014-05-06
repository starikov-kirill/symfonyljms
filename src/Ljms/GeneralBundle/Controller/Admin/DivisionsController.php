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
     * 
     * @Route("/admin/divisions/{limit}", name="divisions", defaults={"limit" = 10})
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request, $limit) 
    {

        //data for filter form 
        $em    = $this->get('doctrine.orm.entity_manager');
        $divisions_list = $em->getRepository('LjmsGeneralBundle:Divisions')->divisionlist();

        // filter form
        $defaultData = array();
        $form = $this->createForm(new DivisionFilterType(), $divisions_list);
        $form->handleRequest($request);

        $data = $form->getData();

        //get filtration divisions
        $divisions = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all')
        {
            $limit_rows = $em->getRepository('LjmsGeneralBundle:Divisions')->countNumber();
        } else 
        {
            $limit_rows = $limit;
        }

        $helper     = $this->get('ljms.helper.pagination');
        $pagination = $helper-> calculate_hash($divisions, $this->get('request')->query->get('page', 1),  $limit_rows);


        return array(
            'form' => $form->createView(), 'divisions' => $pagination, 'limit' => $limit
        );        
    }

    /**
     * 
     * @Route("/admin/add_division", name="add_division")
     * @Template()
     */
	public function addAction(Request $request) 
    {   
        
        $division = new Divisions();

        //connect form
        $form = $this->createForm(new DivisionType(), $division);

            $form->handleRequest($request);

            if ($form->isValid()) 
            {    

                $em = $this->getDoctrine()->getManager();
                $em->persist($division);
                $em->flush();

                return $this->redirect($this->generateUrl('divisions'));
            } 
            $validator = $this->get('validator');
            $errors = $validator->validate($division);

        return array(
                'form' => $form->createView()
        );
    }

    /**
     * 
     * @Route("/admin/edit_division/{id}", requirements={"id" = "\d+"}, name="edit_division")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {          

        $division = $this->get('doctrine')
            ->getManager()
            ->getRepository('LjmsGeneralBundle:Divisions')
            ->find($id);

        if (!$division)
        {
            return $this->redirect($this->generateUrl('divisions'));
        }
        //connect form
        $form = $this->createForm(new DivisionType(), $division);

        $form->handleRequest($request);

        //check the validity of data
        if ($form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('divisions'));
        }

        return array(
                'form' => $form->createView(), 'id' => $id, 'division' => $division
        );
    }

    /**
     * 
     * @Route("/admin/divisions/delete_division/{id}", requirements={"id" = "\d+"}, name="delete_division")
     */      
    public function deleteAction($id)
    {  
        $em = $this->getDoctrine()->getManager();
        $division = $em->getRepository('LjmsGeneralBundle:Divisions')->find($id);

        if (!$division) 
        {
            throw $this->createNotFoundException('No division found for id '.$id);
        } try 
        {
            $em->remove($division);
            $em->flush(); 
            return new Response('TRUE');

        } catch(Exception $e) {

            return new Response('ERROR');

        }        
    }        
}