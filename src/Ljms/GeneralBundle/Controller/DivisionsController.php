<?php

namespace Ljms\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ljms\GeneralBundle\Entity\Divisions;
use Symfony\Component\HttpFoundation\Request;


class DivisionsController extends Controller {


    public function indexAction() {

		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT d.id, d.name, d.fall_ball, d.status FROM LjmsGeneralBundle:Divisions d ORDER BY d.id ASC');

		$divisions = $query->getResult();
        return $this->render('LjmsGeneralBundle:Admin:divisions.html.twig', array(
        	'divisions' => $divisions
    	));        
    }

	public function addAction(Request $request) {   
        $status = array(
            ''  => 'Select one',
            '1'    => 'Active',
            '0' => 'Inactive',
            );

        $age = array(
            5  => 5,
            6  => 6,
            7  => 7,
            8  => 8,
            9  => 9,
            10 => 10,
            11 => 11,
            12 => 12,
            13 => 13,
            14 => 14,
            15 => 15,
            16 => 16,
            17 => 17,
            18 => 18,);

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

                $division->setStatus($form['status']->getData());
                $division->setFallBall($form['fall_ball']->getData());
                $division->setName($form['name']->getData());
                $division->setAgeTo($form['age_to']->getData());
                $division->setAgeFrom($form['age_from']->getData());
                $division->setDescription($form['description']->getData());
                $division->setRules($form['rules']->getData());
                $division->setBaseFee($form['base_fee']->getData());
                $division->setAddonFee($form['addon_fee']->getData());
                $division->setLogo('');

                $em->persist($division);
                $em->flush();

                // perform some action, such as saving the task to the database

                return $this->redirect($this->generateUrl('divisions'));
            } 
        }


        return $this->render('LjmsGeneralBundle:Admin:division_add.html.twig', array(
            'form' => $form->createView(),  'errors' => $errors
        ));
    }

    public function editAction(Request $request, $id) {   
        $status = array(
            ''  => 'Select one',
            '1'    => 'Active',
            '0' => 'Inactive',
            );

        $age = array(
            5  => 5,
            6  => 6,
            7  => 7,
            8  => 8,
            9  => 9,
            10 => 10,
            11 => 11,
            12 => 12,
            13 => 13,
            14 => 14,
            15 => 15,
            16 => 16,
            17 => 17,
            18 => 18,);


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

                $division->setStatus($form['status']->getData());
                $division->setName($form['name']->getData());
                $division->setAgeTo($form['age_to']->getData());
                $division->setAgeFrom($form['age_from']->getData());
                $division->setDescription($form['description']->getData());
                $division->setRules($form['rules']->getData());
                $division->setBaseFee($form['base_fee']->getData());
                $division->setAddonFee($form['addon_fee']->getData());
                $division->setLogo('');

                $em->flush();

                // perform some action, such as saving the task to the database

                return $this->redirect($this->generateUrl('divisions'));
            } 
        }


        return $this->render('LjmsGeneralBundle:Admin:division_edit.html.twig', array(
            'form' => $form->createView(),  'errors' => $errors, 'id' => $id, 'division' => $division
        ));
    


    }
}