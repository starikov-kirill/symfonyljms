<?php

namespace Ljms\GeneralBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ljms\GeneralBundle\Form\Type\DivisionType;
use Ljms\GeneralBundle\Form\Type\DivisionFilterType;
use Ljms\GeneralBundle\Form\Type\MassActionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class DivisionsController extends Controller {

    /**
     * @Route("/admin/divisions/{limit}", name="divisions", defaults={"limit" = 10})
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request, $limit) 
    { 
        $em = $this->get('doctrine.orm.entity_manager');

        // get divisions list
        $divisionsList = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllDevisions();

        $form = $this->createForm(new DivisionFilterType(), $divisionsList);

        $form->handleRequest($request);

        $massActionDD = $this->createForm(new MassActionType());

        $massActionDD->handleRequest($request);

        // get filtration data
        $data = $form->getData();

        // get filtration divisions
        $divisions = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all')
        {
            // if limit = all, count the number of records in db
            $limitRows = $em->getRepository('LjmsGeneralBundle:Divisions')->getCountNumberDivisions();
        } else 
        {
            $limitRows = $limit;
        }

        // connect pagination 
        $helper     = $this->get('ljms.helper.pagination');
        $pagination = $helper-> calculateHash($divisions, $this->get('request')->query->get('page', 1),  $limitRows);

        return array(
            'massActionDD' => $massActionDD->createView(),
            'form'         => $form->createView(), 
            'divisions'    => $pagination, 
            'limit'        => $limit
        );        
    }

    /**
     * @Route("/admin/add_division", name="add_division")
     * @Template()
     */
	public function addAction(Request $request) 
    {           
        $division = new Divisions();

        $form = $this->createForm(new DivisionType(), $division, array('block_name' => 'creating'));

        $form->handleRequest($request);

        // save if form valid
        if ($form->isValid()) 
        {   
            $em = $this->getDoctrine()->getManager();
            $em->persist($division);
            $em->flush();

            return $this->redirect($this->generateUrl('divisions'));
        } 

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/edit_division/{id}", requirements={"id" = "\d+"}, name="edit_division")
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

    /**
     * @Route("/admin/delete_division/{id}", requirements={"id" = "\d+"}, name="delete_division")
     */      
    public function deleteAction($id)
    {    
        // get object from db by id      
        $division = $this->getDoctrine()->getRepository('LjmsGeneralBundle:Divisions')->find($id);

        // if an object no exists
        if (!$division) 
        {
            throw $this->createNotFoundException('No division found for id '.$id);
        } 

        try 
        {   
            $em = $this->getDoctrine()->getManager();
            $em->remove($division);
            $em->flush(); 

            return new Response('TRUE');

        } catch(Exception $e) 
        {
            return new Response($e);
        }        
    } 

    /**
     * @Route("/admin/mass_action", name="divisionMassAction")
     */      
    public function massAction(Request $request)
    {    
        // get id's and action from POST
        $data['ids'] = $request->request->get('division_ids');
        $action = $request->request->get('action');
        $action = $action['action'];

        $em = $this->get('doctrine.orm.entity_manager');

        if ($action == 'delete')
        {
            $result = $em->getRepository('LjmsGeneralBundle:Divisions')->massActionDelete($data);
        } elseif ($action == 'active')
        {
            $data['status'] = '1';
            $result = $em->getRepository('LjmsGeneralBundle:Divisions')->massActionStatus($data);
        } elseif ($action == 'inactive')
        {
            $data['status'] = '0';
            $result = $em->getRepository('LjmsGeneralBundle:Divisions')->massActionStatus($data);
        }
        // if db return 0, set flash massege this error
        if (!$result)
        {
            $this->get('session')->getFlashBag()->add('notice', 'Database error or incorrect command!');
        }

        return $this->redirect($this->generateUrl('divisions'));
    }              
}