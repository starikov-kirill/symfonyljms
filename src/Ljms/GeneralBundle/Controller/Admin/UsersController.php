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
use Ljms\GeneralBundle\Form\Type\UserRolesType;
use Ljms\GeneralBundle\Form\Type\MassActionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;


class UsersController extends Controller {


    /**
     * @Route("/admin/users/{limit}", name="users", defaults={"limit" = 10})
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request, $limit)
    {

        $em  = $this->get('doctrine.orm.entity_manager');

        // get divisions list
        $formFilterData['divisionsList'] = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllDevisions();

        // get roles list
        $formFilterData['roleList'] = $em->getRepository('LjmsGeneralBundle:Role')->findAllRoles();

        $form = $this->createForm(new UserFilterType(), $formFilterData);

        $form->handleRequest($request);

        // generate url for form action
        $currentUrl = $this->getRequest()->getUri();
        $pos = strpos($currentUrl, '/admin/');
        $rest['url'] = (substr($currentUrl, 0, $pos)).'/admin/users';


        $massActionDD = $this->createForm(new MassActionType(), $rest);

        $massActionDD->handleRequest($request);

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
            'massActionDD' => $massActionDD->createView(),
            'form' => $form->createView(),
            'users' => $pagination,
            'limit' => $limit
        );        
    }

    /**
     * @Route("/admin/add_user", name="add_user")
     * @Template()
     */
    public function addAction(Request $request)
    {   
        
        $user = new User();

        $em = $this->getDoctrine()->getManager();

        // get divisions list
        $formData['divisionsList'] = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllDevisions();

        // get teams list
        $formData['teamsList'] = $em->getRepository('LjmsGeneralBundle:Teams')->findAllTeams();

        // get roles list
        $formData['roleList'] = $em->getRepository('LjmsGeneralBundle:Role')->findAllRoles();

        $rolesForm = $this->createForm(new UserRolesType(), $formData);

        $rolesForm->handleRequest($request);

        $form = $this->createForm(new UserType(), $user, array('block_name' => 'creating'));

        $form->handleRequest($request);

        // get sended data
        $data = $request->request->get('user');

        // validation user roles 
        if ($data)
        {
            $error = $this->get('ljms.helper.validationRole')-> ValidateRole($data, '', $formData);

            foreach ($error as $key => $value) {

               $form->get('divisions')->addError(new FormError($value));
            }
        }
       
        // process data and save if form valid
        if ($form->isValid()) {
            
            // encrypt password
            $password = $this->get('ljms.helper.encryptPassword')-> encryptPassword($user, 'add');

            // install user is active by default
            $user->setIsActive('1');

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('users'));
        } 

        return array(
            'rolesForm' => $rolesForm->createView(),
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/edit_user/{id}", requirements={"id" = "\d+"}, name="edit_user")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {   

        $em = $this->getDoctrine()->getManager();

        // get object from db by id */
        $user = $this->getDoctrine()->getRepository('LjmsGeneralBundle:User')->find($id);

        // if an object no exists
        if (!$user) 
        {
            return $this->redirect($this->generateUrl('users'));
        }
        // get divisions list
        $formData['divisionsList'] = $em->getRepository('LjmsGeneralBundle:Divisions')->findAllDevisions();

        // get teams list
        $formData['teamsList'] = $em->getRepository('LjmsGeneralBundle:Teams')->findAllTeams();

        // get roles list
        $formData['roleList'] = $em->getRepository('LjmsGeneralBundle:Role')->findAllRoles();


        $rolesForm = $this->createForm(new UserRolesType(), $formData);

        $rolesForm->handleRequest($request);

        $form = $this->createForm(new UserType(), $user, array('block_name' => 'updating'));

        $form->handleRequest($request);

        // get the password entered in the form 
        $newPassword = $user->getNewpassword();

        // get sended data
        $data = $request->request->get('user');

        // validation user roles 
        if ($data)
        {
            $error = $this->get('ljms.helper.validationRole')-> ValidateRole($data, $id, $formData);

            foreach ($error as $key => $value) {

               $form->get('divisions')->addError(new FormError($value));
            }
        }

        if ($form->isValid()) {
            // if the password has been entered 
            if ($newPassword) 
            {
                // encrypt password and written to the database
                $password = $this->get('ljms.helper.encryptPassword')-> encryptPassword($user, 'edit');
            }

            $em->flush();

            return $this->redirect($this->generateUrl('users'));
        } 

        return array(
//            'roles' => $roles,
            'rolesForm' => $rolesForm->createView(),
            'form' => $form->createView(),
            'id' => $id,
            'user' => $user
        );
    }

    /**
     * @Route("/admin/delete_user/{id}", requirements={"id" = "\d+"}, name="delete_user")
     */  
    public function deleteAction($id)
    {        
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

    /**
     * @Route("/admin/users/email_jq_check/{id}", requirements={"id" = "\d+"}, name="email_jq_check")
     */  
    public function emailJqCheck(Request $request, $id)
    {   

        $post = $request->request->get('user');
        $email = $post['email']['first'];

        $em  = $this->get('doctrine.orm.entity_manager');
        // get id from db by email
        $newid = $em->getRepository('LjmsGeneralBundle:User')->emailJqCheck($email);

        if ($newid == 'no' || $newid == $id)
        {
            return new Response('true');
        } else 
        {
            return new Response('false');   
        }       
         
    }

    /**
     * @Route("/admin/users/mass_action", name="userMassAction")
     */      
    public function massAction(Request $request)
    {    
        // get id's and action from POST
        $data['ids'] = $request->request->get('user_ids');
        $action = $request->request->get('action');
        $action = $action['action'];

        $em = $this->get('doctrine.orm.entity_manager');

        if ($action == 'delete')
        {
            $result = $em->getRepository('LjmsGeneralBundle:User')->massActionDelete($data);
        } elseif ($action == 'active')
        {
            $data['status'] = '1';
            $result = $em->getRepository('LjmsGeneralBundle:User')->massActionStatus($data);
        } elseif ($action == 'inactive')
        {
            $data['status'] = '0';
            $result = $em->getRepository('LjmsGeneralBundle:User')->massActionStatus($data);
        }
        // if db return 0, set flash massege this error
        if (!$result)
        {
            $this->get('session')->getFlashBag()->add('notice', 'Database error or incorrect command!');
        }

        return $this->redirect($this->generateUrl('users'));
    }   

}