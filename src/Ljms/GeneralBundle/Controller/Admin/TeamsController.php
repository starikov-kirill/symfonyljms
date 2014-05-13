<?php

namespace Ljms\GeneralBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Teams;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ljms\GeneralBundle\Form\Type\TeamType;
use Ljms\GeneralBundle\Form\Type\TeamFilterType;
use Ljms\GeneralBundle\Form\Type\MassActionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class TeamsController extends Controller {


    /**
     * @Route("/admin/teams/{limit}", name="teams", defaults={"limit" = 10})
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request, $limit) 
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // get divisions list
        $divisionsList = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllDevisions();

        $form = $this->createForm(new TeamFilterType(), $divisionsList);

        $form->handleRequest($request);

        // generate url for form action
        $currentUrl = $this->getRequest()->getUri();
        $pos = strpos($currentUrl, '/admin/');
        $rest['url'] = (substr($currentUrl, 0, $pos)).'/admin/teams';


        $massActionDD = $this->createForm(new MassActionType(), $rest);

        $massActionDD->handleRequest($request);

        // get filtration data
        $data = $form->getData();

        // get filtration teams
        $teams = $em->getRepository('LjmsGeneralBundle:Teams')->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all')
        {
            // if limit = all, count the number of records in db
            $limitRows = $em->getRepository('LjmsGeneralBundle:Teams')->getCountNumberTeams();
        } else 
        {
            $limitRows = $limit;
        }

        // connect pagination 
        $helper     = $this->get('ljms.helper.pagination');
        $pagination = $helper-> calculateHash($teams, $this->get('request')->query->get('page', 1),  $limitRows);

        return array(
            'massActionDD' => $massActionDD->createView(),
            'form' => $form->createView(),
            'teams' => $pagination,
            'limit' => $limit
        );        
    }

    /**
     * @Route("/admin/add_team", name="add_team")
     * @Template()
     */
    public function addAction(Request $request) {   
        
        $team = new Teams();

        $form = $this->createForm(new TeamType(), $team);

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
     * @Route("/admin/edit_team/{id}", requirements={"id" = "\d+"}, name="edit_team")
     * @Template()
     */
    public function editAction(Request $request, $id) {   
        
        // get object from db by id
        $team = $this->getDoctrine()->getRepository('LjmsGeneralBundle:Teams')->find($id);

        // if an object no exists
        if (!$team) {
            return $this->redirect($this->generateUrl('teams'));
        }

        $form = $this->createForm(new TeamType(), $team);

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
     * @Route("/admin/delete_team/{id}", requirements={"id" = "\d+"}, name="delete_team")
     */  
    public function deleteAction($id) {  
        
        // get object from db by id
        $team = $this->getDoctrine()->getRepository('LjmsGeneralBundle:Teams')->find($id);

        // if an object no exists
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
            return new Response($e);
        }        
    }

    /**
     * @Route("/admin/teams/mass_action", name="divisionMassAction")
     */      
    public function massAction(Request $request)
    {    
        // get id's and action from POST
        $data['ids'] = $request->request->get('team_ids');
        $action = $request->request->get('action');
        $action = $action['action'];

        $em = $this->get('doctrine.orm.entity_manager');

        if ($action == 'delete')
        {
            $result = $em->getRepository('LjmsGeneralBundle:Teams')->massActionDelete($data);
        } elseif ($action == 'active')
        {
            $data['status'] = '1';
            $result = $em->getRepository('LjmsGeneralBundle:Teams')->massActionStatus($data);
        } elseif ($action == 'inactive')
        {
            $data['status'] = '0';
            $result = $em->getRepository('LjmsGeneralBundle:Teams')->massActionStatus($data);
        }
        // if db return 0, set flash massege this error
        if (!$result)
        {
            $this->get('session')->getFlashBag()->add('notice', 'Database error or incorrect command!');
        }

        return $this->redirect($this->generateUrl('teams'));
    }              
}