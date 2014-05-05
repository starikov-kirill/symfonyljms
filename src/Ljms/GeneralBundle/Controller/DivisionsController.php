<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ljms\GeneralBundle\Form\Type\DivisionType;
use Ljms\GeneralBundle\Form\Type\DivisionFilterType;


class DivisionsController extends Controller {




    public function indexAction(Request $request, $limit) {

        //data for filter form 
        $em    = $this->get('doctrine.orm.entity_manager');
        $divisions_list = $em->getRepository('LjmsGeneralBundle:Divisions')
            ->divisionlist();

        // filter form
        $defaultData = array();
        $form = $this->createForm(new DivisionFilterType(), $divisions_list);

        $form->handleRequest($request);

        //get filtration divisions
        $data = $form->getData();

        $divisions = $em->getRepository('LjmsGeneralBundle:Divisions')
            ->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all') {
            $limit_rows = $em->getRepository('LjmsGeneralBundle:Divisions')
                ->countNumber();
        } else {
            $limit_rows = $limit;
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $divisions,
            $this->get('request')->query->get('page', 1)/*page number*/,
            $limit_rows/*limit per page*/
        );
        return $this->render('LjmsGeneralBundle:Admin:divisions.html.twig', array(
            'form' => $form->createView(), 'divisions' => $pagination, 'limit' => $limit

    	));        
    }


	public function addAction(Request $request) {   
        
        $division = new Divisions();
        $em = $this->getDoctrine()->getManager();

        //connect form
        $form = $this->createForm(new DivisionType(), $division);


            $form->handleRequest($request);


            if ($form->isValid()) {      

                $em->persist($division);
                $em->flush();

                return $this->redirect($this->generateUrl('divisions'));
            } 


        return $this->render('LjmsGeneralBundle:Admin:division_add.html.twig', array(
            'form' => $form->createView(),  
        ));
    }

    public function editAction(Request $request, $id) {          

        $em = $this->getDoctrine()->getManager();
        $division = $this->get('doctrine')
            ->getManager()
            ->getRepository('LjmsGeneralBundle:Divisions')
            ->find($id);

        if (!$division) {
            return $this->redirect($this->generateUrl('divisions'));
        }
        //connect form
        $form = $this->createForm(new DivisionType(), $division);

        $errors='';
       
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            //check the validity of data
                $validator = $this->get('validator');
                $errors = $validator->validate($division);
            if ($form->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('divisions'));
            } 
        }

        return $this->render('LjmsGeneralBundle:Admin:division_edit.html.twig', array(
            'form' => $form->createView(),  'errors' => $errors, 'id' => $id, 'division' => $division
        ));
    }

    public function deleteAction($id) {  
        $em = $this->getDoctrine()->getManager();
        $division = $em->getRepository('LjmsGeneralBundle:Divisions')->find($id);

        if (!$division) {
            throw $this->createNotFoundException('No division found for id '.$id);
        } try{
            $em->remove($division);
            $em->flush(); 
            return new Response('TRUE');

        } catch(Exception $e){

            return new Response('ERROR');

        }        
    }        
}