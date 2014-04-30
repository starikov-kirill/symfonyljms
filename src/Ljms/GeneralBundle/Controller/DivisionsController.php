<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ljms\GeneralBundle\Form\Type\DivisionType;


class DivisionsController extends Controller {


    public function indexAction(Request $request, $limit) {

        $status_filter = $this->status_filter;
        $season_filter = $this->season_filter;

        //data for filter form 
        $em    = $this->get('doctrine.orm.entity_manager');

        $divisions_list = $em->getRepository('LjmsGeneralBundle:Divisions')
            ->divisionlist();

        // filter form
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->setMethod('GET')
            ->add('divisions', 'choice', array('choices'   => $divisions_list, 'required'  => false, 'attr' => array('class' => 'select_wide')))
            ->add('status', 'choice', array('choices'   => $status_filter, 'required'  => false, 'attr' => array('class' => 'select_wide')))
            ->add('season', 'choice', array('choices' => $season_filter, 'required'  => false, 'attr' => array('class' => 'select_wide')))
            ->add('filter', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();

        $form->handleRequest($request);

        //get filtration divisions
        $filter_status = '';
        $filter_season = '';
        $filter_division = '';
        $data = $form->getData();

        $divisions = $em->getRepository('LjmsGeneralBundle:Divisions')
            ->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all') {
            $res = $em->createQuery("SELECT COUNT(d) FROM LjmsGeneralBundle:Divisions d");
            $limit_rows = $res->getResult();
            $limit_rows = $limit_rows[0][1];
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

        $errors='';
       
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            //check the validity of data
                $validator = $this->get('validator');
                $errors = $validator->validate($division);
            if ($form->isValid()) {     

                $em->persist($division);
                $em->flush();

                return $this->redirect($this->generateUrl('divisions'));
            } 
        }

        return $this->render('LjmsGeneralBundle:Admin:division_add.html.twig', array(
            'form' => $form->createView(),  'errors' => $errors
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
        private $status_filter = array(''  => 'All', '1'    => 'Active', '0' => 'Inactive');

        private $season_filter = array(''  => 'All', '0'    => 'Standart', '1' => 'Fall Ball');
        
}