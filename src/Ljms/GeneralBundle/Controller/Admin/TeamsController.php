<?php

namespace Ljms\GeneralBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Teams;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ljms\GeneralBundle\Form\Type\TeamType;
use Ljms\GeneralBundle\Form\Type\TeamFilterType;
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
    public function indexAction(Request $request, $limit) 
    {
        $em    = $this->get('doctrine.orm.entity_manager');

        // get divisions list
        $divisions_list = $em->getRepository('LjmsGeneralBundle:Divisions')->divisionlist();

        // create form
        $form = $this->createForm(new TeamFilterType(), $divisions_list);

        // form proccessing
        $form->handleRequest($request);

        // get filtration data
        $data = $form->getData();

        // get filtration teams
        $teams = $em->getRepository('LjmsGeneralBundle:Teams')->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all')
        {
            // if limit = all, count the number of records in db
            $limit_rows = $em->getRepository('LjmsGeneralBundle:Teams')->countNumber();
        } else 
        {
            $limit_rows = $limit;
        }

        // connect pagination 
        $helper     = $this->get('ljms.helper.pagination');
        $pagination = $helper-> calculate_hash($teams, $this->get('request')->query->get('page', 1),  $limit_rows);

        return array('form' => $form->createView(), 'teams' => $pagination, 'limit' => $limit);        
    }

    /**
     * 
     * @Route("/admin/add_team", name="add_team")
     * @Template()
     */
    public function addAction(Request $request) {   
        
        $team = new Teams();

        // create form
        $form = $this->createForm(new TeamType(), $team);

        // form proccessing
        $form->handleRequest($request);
       
        // save if form valid
        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            return $this->redirect($this->generateUrl('teams'));
        } 

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * 
     * @Route("/admin/edit_team/{id}", requirements={"id" = "\d+"}, name="edit_team")
     * @Template()
     */
    public function editAction(Request $request, $id) {   
        
        // get object from db by id
        $team = $this->getDoctrine()->getRepository('LjmsGeneralBundle:Teams')->find($id);

        // if an object exists
        if (!$team) {
            return $this->redirect($this->generateUrl('teams'));
        }

        // create form
        $form = $this->createForm(new TeamType(), $team);

        // form proccessing
        $form->handleRequest($request);
        
        // check the validity of data
        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('teams'));
        } 

        return array(
            'form' => $form->createView(),
            'id' => $id,
            'team' => $team
        );
    }

    /**
     * 
     * @Route("/admin/delete_team/{id}", requirements={"id" = "\d+"}, name="delete_team")
     */  
    public function deleteAction($id) {  
        
        // get object from db by id
        $team = $this->getDoctrine()->getRepository('LjmsGeneralBundle:Teams')->find($id);

        // if an object exists
        if (!$team)
        {
            throw $this->createNotFoundException('No team found for id '.$id);
        }

        try
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($team);
            $em->flush(); 
            return new Response('TRUE');

        } catch(Exception $e)
        {
            return new Response('ERROR');
        }        
    }
}