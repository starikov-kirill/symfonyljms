<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\User;
use Ljms\GeneralBundle\Entity\Teams;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UsersController extends Controller {


    public function indexAction(Request $request, $limit) {

        $league_filter = $this->league_filter;

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
            ->add('league', 'choice', array('choices' => $league_filter, 'required'  => false, 'attr' => array('class' => 'select_wide')))
            ->add('filter', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
        $form->handleRequest($request);

        //get filtration users
        $filter_role = '';
        $filter_division = '';
        $data = $form->getData();
        $repository = $this->getDoctrine()
            ->getRepository('LjmsGeneralBundle:User');

        $query = $repository->createQueryBuilder('u')
            ->select('u', 'u.username', 'u.isActive', 'u.home_phone as phone', 'u.id as id', 'u.email');
           // ->leftJoin ('t.division_id', 'd')
           // ->leftJoin ('t.league_type_id', 'l');
/*
        if ($data) {
            if ($data['divisions']) {
                $query->andWhere('t.division_id='.$data['divisions']);
            }
            if (strlen($data['league'])) {   
                $query->andWhere('t.league_type_id='.$data['league']);
            }
            if (strlen($data['status'])) {   
                $query->andWhere('t.status='.$data['status']);
            }
        }*/

        $query->getQuery();
        // number of rows
        if ($limit == 'all') {
            $res = $em->createQuery("SELECT COUNT(u) FROM LjmsGeneralBundle:User u");
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


        return $this->render('LjmsGeneralBundle:Admin:users.html.twig', array('form' => $form->createView(), 'users' => $pagination, 'limit' => $limit));        
    }

    public function addAction(Request $request) {   
        
        $user = new User();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($user)
            ->add('username', 'text', array( 'attr' => array('class' => 'select_wide')))
            ->add('password', 'repeated', array('type' => 'password', 'invalid_message' => 'Passwords do not match', 'options' => array('attr' => array('class' => 'select_wide'))))
            ->add('last_name', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('address', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('zipcode', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('city', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('alt_first_name', 'text', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('alt_last_name', 'text', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('home_phone', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('cell_phone', 'text', array( 'attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('alt_phone', 'text', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('alt_phone_2', 'text', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('alt_email', 'email', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('states_id', 'entity', array('class' => 'LjmsGeneralBundle:States', 'property' => 'name', 'attr' => array('class' => 'select_wide')))
            ->add('email', 'repeated', array('type' => 'email', 'invalid_message' => 'Emails do not match', 'options' => array('attr' => array('class' => 'select_wide'))))
            ->add('save', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
            $errors='';
       
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            //check the validity of data
                $validator = $this->get('validator');
                $errors = $validator->validate($user);
            if ($form->isValid()) {
                
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $user->setIsActive('1');
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('users'));
            } 
        }

        return $this->render('LjmsGeneralBundle:Admin:user_add.html.twig', array(
            'form' => $form->createView(),  'errors' => $errors
        ));
    }

    public function editAction(Request $request, $id) {   
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('doctrine')
            ->getManager()
            ->getRepository('LjmsGeneralBundle:User')
            ->find($id);

        if (!$user) {
            return $this->redirect($this->generateUrl('users'));
        }

        $form = $this->createFormBuilder($user)
            ->add('username', 'text', array( 'attr' => array('class' => 'select_wide')))
            ->add('password', 'repeated', array('type' => 'password', 'invalid_message' => 'Passwords do not match', 'options' => array('attr' => array('class' => 'select_wide'))))
            ->add('last_name', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('address', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('zipcode', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('city', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('alt_first_name', 'text', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('alt_last_name', 'text', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('home_phone', 'text', array('attr' => array('class' => 'select_wide')))
            ->add('cell_phone', 'text', array( 'attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('alt_phone', 'text', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('alt_phone_2', 'text', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('alt_email', 'email', array('attr' => array('class' => 'select_wide'), 'required'  => false))
            ->add('states_id', 'entity', array('class' => 'LjmsGeneralBundle:States', 'property' => 'name', 'attr' => array('class' => 'select_wide')))
            ->add('email', 'repeated', array('type' => 'email', 'invalid_message' => 'Emails do not match', 'options' => array('attr' => array('class' => 'select_wide'))))
            ->add('save', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
            $errors='';
       
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            //check the validity of data
                $validator = $this->get('validator');
                $errors = $validator->validate($user);
            if ($form->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('users'));
            } 
        }

        return $this->render('LjmsGeneralBundle:Admin:user_edit.html.twig', array(
            'form' => $form->createView(),  'errors' => $errors, 'id' => $id, 'user' => $user
        ));
    }

    public function deleteAction($id) {  
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('LjmsGeneralBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }

        try{

            $em->remove($user);
            $em->flush(); 
            return new Response('TRUE');

        } catch(Exception $e){

            return new Response('ERROR');

        }        
    }


    private $visitor = array('0'    => 'No', '1' => 'Yes');

    private $status = array(''  => 'Select one', '1'    => 'Active', '0' => 'Inactive');

	private $league_filter = array(''  => 'All', '1'    => 'LJMS Teams', '2' => 'Non Conference Teams');

	private $status_filter = array(''  => 'All', '1'    => 'Active', '0' => 'Inactive');
}