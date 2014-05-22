<?php

namespace Ljms\GeneralBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Divisions;
use Ljms\GeneralBundle\Entity\Teams;
use Ljms\GeneralBundle\Entity\Games;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Ljms\GeneralBundle\Form\Type\DivisionType;
use Ljms\GeneralBundle\Form\Type\GameFilterType;
use Ljms\GeneralBundle\Form\Type\MassActionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GamesController extends Controller {

	/**
     * @Route("/admin/games/{limit}", name="games", defaults={"limit" = 10})
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request, $limit) 
    {
    	$em = $this->get('doctrine.orm.entity_manager');

        // get divisions list
        $filterData['divisionsList'] = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllDevisions();

		// get teams list
        $filterData['teamsList'] = $em->getRepository('LjmsGeneralBundle:Teams')->findAllTeams();

        $form = $this->createForm(new GameFilterType(), $filterData);

        $form->handleRequest($request);

        // generate url for form action
        $currentUrl = $this->getRequest()->getUri();
        $pos = strpos($currentUrl, '/admin/');
        $rest['url'] = (substr($currentUrl, 0, $pos)).'/admin/games';

        $massActionDD = $this->createForm(new MassActionType(), $rest);

        $massActionDD->handleRequest($request);

        // get filtration data
        $data = $form->getData();

        // get filtration divisions
        $games = $em->getRepository('LjmsGeneralBundle:Games')->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all')
        {
            // if limit = all, count the number of records in db
            $limitRows = $em->getRepository('LjmsGeneralBundle:Games')->getCountNumberGames();
        } else 
        {
            $limitRows = $limit;
        }


    	// connect pagination 
        $helper     = $this->get('ljms.helper.pagination');
        $pagination = $helper-> calculateHash($games, $this->get('request')->query->get('page', 1),  $limitRows);

        return array(
            'massActionDD' => $massActionDD->createView(),
            'form'         => $form->createView(), 
            'games'    => $pagination, 
            'limit'        => $limit
        );  
    }

    /**
     * @Route("/admin/edit_game/{id}", requirements={"id" = "\d+"}, name="edit_game")
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

        $form = $this->createForm(new DivisionType(), $division, array('block_name' => 'updating'));

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

}