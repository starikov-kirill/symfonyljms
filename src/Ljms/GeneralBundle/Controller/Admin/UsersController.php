<?php

namespace Ljms\GeneralBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\User;
use Ljms\GeneralBundle\Entity\Teams;
use Ljms\GeneralBundle\Entity\Divisions;
use Ljms\GeneralBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ljms\GeneralBundle\Form\Type\UserType;
use Ljms\GeneralBundle\Form\Type\UserFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class UsersController extends Controller {


    /**
     * @Route("/admin/users/{limit}", name="users", defaults={"limit" = 10})
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request, $limit) {

        $em  = $this->get('doctrine.orm.entity_manager');

        // get divisions list
        $formFilterData['divisionsList'] = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllDevisions();

        // get roles list
        $formFilterData['roleList'] = $em->getRepository('LjmsGeneralBundle:Role')->findAllRoles();

        $form = $this->createForm(new UserFilterType(), $formFilterData);

        $form->handleRequest($request);

        // get filtration data
        $data = $form->getData();

        // get filtration users
        $users = $em->getRepository('LjmsGeneralBundle:User')->findAllThisFilter($data);

        // number of rows
        if ($limit == 'all')
        {
            // if limit = all, count the number of records in db
            $limitRows = $em->getRepository('LjmsGeneralBundle:User')->getCountNumberUsers();
        } else 
        {
            $limitRows = $limit;
        }

        // connect pagination 
        $helper     = $this->get('ljms.helper.pagination');
        $pagination = $helper-> calculateHash($users, $this->get('request')->query->get('page', 1),  $limitRows);

        return array(
            'form' => $form->createView(),
            'users' => $pagination,
            'limit' => $limit
        );        
    }

    /**
     * @Route("/admin/add_user", name="add_user")
     * @Template()
     */
    public function addAction(Request $request) {   
        
        $user = new User();

        $form = $this->createForm(new UserType(), $user, array('block_name' => 'creating'));

        $form->handleRequest($request);
       
        // process data and save if form valid
        if ($form->isValid()) {
            
            // encrypt password
            $password = $this->get('ljms.helper.encryptPassword')-> encryptPassword($user, 'add');

            // install user is active by default
            $user->setIsActive('1');

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('users'));
        } 

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/admin/edit_user/{id}", requirements={"id" = "\d+"}, name="edit_user")
     * @Template()
     */
    public function editAction(Request $request, $id) {   

        // get object from db by id
        $user = $this->getDoctrine()->getRepository('LjmsGeneralBundle:User')->find($id);

        // if an object no exists
        if (!$user) 
        {
            return $this->redirect($this->generateUrl('users'));
        }

        $form = $this->createForm(new UserType(), $user, array('block_name' => 'updating'));

        $form->handleRequest($request);

        // get the password entered in the form 
        $newPassword = $user->getNewpassword();

        if ($form->isValid()) {
            // if the password has been entered 
            if ($newPassword) 
            {
                // encrypt password and written to the database
                $password = $this->get('ljms.helper.encryptPassword')-> encryptPassword($user, 'edit');
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('users'));
        } 

        return array(
            'form' => $form->createView(),
            'id' => $id,
            'user' => $user
        );
    }

    /**
     * @Route("/admin/users/delete_user/{id}", requirements={"id" = "\d+"}, name="delete_user")
     */  
    public function deleteAction($id) {  
        
        // get object from db by id      
        $user = $this->getDoctrine()->getRepository('LjmsGeneralBundle:User')->find($id);

        // if an object no exists
        if (!$user) 
        {
            throw $this->createNotFoundException('No user found for id '.$id);
        }
        try
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            return new Response('TRUE');

        } catch(Exception $e)
        {
            return new Response($e);
        }        
    }

}