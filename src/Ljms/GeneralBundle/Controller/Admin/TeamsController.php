<?php

namespace Ljms\GeneralBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Teams;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ljms\GeneralBundle\Form\Type\TeamType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class TeamsController extends Controller {


    /**
     * 
     * @Route("/admin/teams/{limit}", name="teams", defaults={"limit" = 10})
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request, $limit) {

        $status_filter = $this->status_filter;
        $league_filter = $this->league_filter;

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
            ->add('league', 'choice', array('choices' => $league_filter, 'required'  => false, 'attr' => array('class' => 'select_wide')))
            ->add('filter', 'submit', array('attr' => array('class' => 'button')))
            ->getForm();
        $form->handleRequest($request);

        //get filtration teams
        $filter_status = '';
        $filter_league = '';
        $filter_division = '';
        $data = $form->getData();

        $teams = $em->getRepository('LjmsGeneralBundle:Teams')
            ->findAllThisFilter($data);

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
            $teams,
            $this->get('request')->query->get('page', 1)/*page number*/,
            $limit_rows/*limit per page*/
        );


        return array('form' => $form->createView(), 'teams' => $pagination, 'limit' => $limit);        
    }

    /**
     * 
     * @Route("/admin/add_team", name="add_team")
     * @Template()
     */
    public function addAction(Request $request) {   
        
        $team = new Teams();
        $em = $this->getDoctrine()->getManager();

        //connect form
        $form = $this->createForm(new TeamType(), $team);
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

        return array(
            'form' => $form->createView(),  'errors' => $errors
        );
    }

    /**
     * 
     * @Route("/admin/edit_team/{id}", requirements={"id" = "\d+"}, name="edit_team")
     * @Template()
     */
    public function editAction(Request $request, $id) {   
        
        $em = $this->getDoctrine()->getManager();
        $team = $this->get('doctrine')
            ->getManager()
            ->getRepository('LjmsGeneralBundle:Teams')
            ->find($id);

        if (!$team) {
            return $this->redirect($this->generateUrl('teams'));
        }

        //connect form
        $form = $this->createForm(new TeamType(), $team);
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

        return array(
            'form' => $form->createView(),  'errors' => $errors, 'id' => $id, 'team' => $team
        );
    }

    /**
     * 
     * @Route("/admin/teams/delete_team/{id}", requirements={"id" = "\d+"}, name="delete_team")
     */  
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

	private $league_filter = array(''  => 'All', '1'    => 'LJMS Teams', '2' => 'Non Conference Teams');

	private $status_filter = array(''  => 'All', '1'    => 'Active', '0' => 'Inactive');
}