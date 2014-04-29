<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Teams;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TeamsController extends Controller {


    public function indexAction(Request $request, $limit) {

        $status_filter = $this->status_filter;
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
            ->add('status', 'choice', array('choices'   => $status_filter, 'required'  => false, 'attr' => array('class' => 'select_wide')))
            ->add('league', 'choice', array('choices' => $league_filter, 'required'  => false, 'attr' => array('class' => 'select_wide')))
            ->add('filter', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
        $form->handleRequest($request);

        //get filtration teams
        $filter_status = '';
        $filter_league = '';
        $filter_division = '';
        $data = $form->getData();
        $repository = $this->getDoctrine()
            ->getRepository('LjmsGeneralBundle:Teams');

        $query = $repository->createQueryBuilder('t')
            ->select('t', 't.name  as team_name', 'd.name as division_name', 't.status', 't.id as id', 'l.name as league_name')
            ->leftJoin ('t.division_id', 'd')
            ->leftJoin ('t.league_type_id', 'l');

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
        }

        $query->getQuery();
        // number of rows
        if ($limit == 'all') {
            $res = $em->createQuery("SELECT COUNT(t) FROM LjmsGeneralBundle:Teams t");
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


        return $this->render('LjmsGeneralBundle:Admin:teams.html.twig', array('form' => $form->createView(), 'teams' => $pagination, 'limit' => $limit));        
    }

    public function addAction(Request $request) {   
        
        $status = $this->status;
        $visitor = $this->visitor;

        $team = new Teams();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($team)
            ->add('status', 'choice', array('choices'   => $status, 'attr' => array('class' => 'select_wide')))
            ->add('is_visitor', 'choice', array('choices'   => $visitor, 'attr' => array('class' => 'select_wide')))
            ->add('division_id', 'entity', array('class' => 'LjmsGeneralBundle:Divisions', 'property' => 'name','attr' => array('class' => 'select_wide')))
            ->add('league_type_id', 'entity', array('class' => 'LjmsGeneralBundle:Leagues', 'property' => 'name', 'attr' => array('class' => 'select_wide')))
            ->add('name', 'text',  array('attr' => array('class' => 'select_wide')))
            ->add('save', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
            $errors='';
       
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            //check the validity of data
                $validator = $this->get('validator');
                $errors = $validator->validate($team);
            if ($form->isValid()) {

                $em->persist($team);
                $em->flush();

                return $this->redirect($this->generateUrl('teams'));
            } 
        }

        return $this->render('LjmsGeneralBundle:Admin:team_add.html.twig', array(
            'form' => $form->createView(),  'errors' => $errors
        ));
    }

    public function editAction(Request $request, $id) {   
        
        $status = $this->status;
        $visitor = $this->visitor;

        $em = $this->getDoctrine()->getManager();
        $team = $this->get('doctrine')
            ->getManager()
            ->getRepository('LjmsGeneralBundle:Teams')
            ->find($id);

        if (!$team) {
            return $this->redirect($this->generateUrl('teams'));
        }

        $form = $this->createFormBuilder($team)
            ->add('status', 'choice', array('choices'   => $status, 'attr' => array('class' => 'select_wide')))
            ->add('is_visitor', 'choice', array('choices'   => $visitor, 'attr' => array('class' => 'select_wide')))
            ->add('division_id', 'entity', array('class' => 'LjmsGeneralBundle:Divisions', 'property' => 'name','attr' => array('class' => 'select_wide')))
            ->add('league_type_id', 'entity', array('class' => 'LjmsGeneralBundle:Leagues', 'property' => 'name', 'attr' => array('class' => 'select_wide')))
            ->add('name', 'text',  array('attr' => array('class' => 'select_wide')))
            ->add('save', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
            $errors='';
       
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            //check the validity of data
                $validator = $this->get('validator');
                $errors = $validator->validate($team);
            if ($form->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('teams'));
            } 
        }

        return $this->render('LjmsGeneralBundle:Admin:team_edit.html.twig', array(
            'form' => $form->createView(),  'errors' => $errors, 'id' => $id, 'team' => $team
        ));
    }

    public function deleteAction($id) {  
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('LjmsGeneralBundle:Teams')->find($id);

        if (!$team) {
            throw $this->createNotFoundException('No team found for id '.$id);
        }

        try{

            $em->remove($team);
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