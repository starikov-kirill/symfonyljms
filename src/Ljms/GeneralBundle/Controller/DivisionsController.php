<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DivisionsController extends Controller {


    public function indexAction(Request $request, $limit) {

        $status_filter = $this->status_filter;
        $season_filter = $this->season_filter;

        //data for filter form 
        $em    = $this->get('doctrine.orm.entity_manager');
        $query_divisions = $em->createQuery('SELECT d.id, d.name FROM LjmsGeneralBundle:Divisions d ORDER BY d.id ASC');
        $divisions = $query_divisions->getResult();
        $divisions_list[''] = 'All';
        foreach ($divisions as $key => $value) {
            $divisions_list[$divisions[$key]['id']] = $divisions[$key]['name'];
        }

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
        $repository = $this->getDoctrine()
            ->getRepository('LjmsGeneralBundle:Divisions');

        $query = $repository->createQueryBuilder('d')
            ->Where('1 = 1')
            ->select('d');

            if ($data) {
                if ($data['divisions']) {
                    $query->andWhere('d.id='.$data['divisions']);
                }
                if (strlen($data['season'])) {   
                    $query->andWhere('d.fall_ball='.$data['season']);
                }
                if (strlen($data['status'])) {   
                    $query->andWhere('d.status='.$data['status']);
                }
            }
        $query->getQuery();

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
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            $limit_rows/*limit per page*/
        );
        return $this->render('LjmsGeneralBundle:Admin:divisions.html.twig', array(
            'form' => $form->createView(), 'divisions' => $pagination, 'limit' => $limit

    	));        
    }

	public function addAction(Request $request) {   
        
        $status = $this->status;
        $age = $this->age;

        $division = new Divisions();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($division)
            ->add('status', 'choice', array('choices'   => $status, 'attr' => array('class' => 'select_wide')))
            ->add('fall_ball', 'checkbox', array('required'=> false))
            ->add('name', 'text')
            ->add('age_to', 'choice', array('choices'   => $age))
            ->add('age_from', 'choice', array('choices'   => $age))
            ->add('description', 'textarea', array ('attr' => array('cols' => '40', 'rows' => '10'), 'required'=>false))
            ->add('rules', 'textarea', array ('attr' => array('cols' => '40', 'rows' => '10'), 'required'=>false))
            ->add('base_fee', 'text', array('required'=> false))
            ->add('addon_fee', 'text', array('required'=> false))
            ->add('save', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
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
        
        $status = $this->status;
        $age = $this->age;

        $em = $this->getDoctrine()->getManager();
        $division = $this->get('doctrine')
            ->getManager()
            ->getRepository('LjmsGeneralBundle:Divisions')
            ->find($id);

        if (!$division) {
            return $this->redirect($this->generateUrl('divisions'));
        }


        $form = $this->createFormBuilder($division)
            ->add('status', 'choice', array('choices'   => $status, 'attr' => array('class' => 'select_wide')))
            ->add('name', 'text')
            ->add('age_to', 'choice', array('choices'   => $age))
            ->add('age_from', 'choice', array('choices'   => $age))
            ->add('description', 'textarea', array ('attr' => array('cols' => '40', 'rows' => '10'), 'required'=>false))
            ->add('rules', 'textarea', array ('attr' => array('cols' => '40', 'rows' => '10'), 'required'=>false))
            ->add('base_fee', null, array('required'=> false))
            ->add('addon_fee', null, array('required'=> false))
            ->add('save', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
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
        }

        try{

            $em->remove($division);
            $em->flush(); 
            return new Response('TRUE');

        } catch(Exception $e){

            return new Response('ERROR');

        }        
    }




        private $age = array(5  => 5, 6  => 6, 7  => 7, 8  => 8, 9  => 9, 10 => 10, 11 => 11, 
                             12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17, 18 => 18
                            );

        private $status = array(''  => 'Select one', '1'    => 'Active', '0' => 'Inactive');

        private $status_filter = array(''  => 'All', '1'    => 'Active', '0' => 'Inactive');

        private $season_filter = array(''  => 'All', '0'    => 'Standart', '1' => 'Fall Ball');
        
}