<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserRolesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $data = $builder->getData();

		$builder->setMethod('GET')
            ->add('divisions', 'choice', array(
                'choices'   => $data['divisionsList'],
                'empty_value' => 'Select',
                'required'  => false,
                'attr' => array(
                    'disabled' => true,
                    'class' => 'divisions_dd select_100px'
                    )
                )
            )
            ->add('roles', 'choice', array(
                'choices' => $data['roleList'],
                'required'  => false,
                'empty_value' => 'Select',
                'attr' => array(
                    'class' => 'roles_dd select_100px'
                    )
                )
            )
            ->add('teams', 'choice', array(
                'required'  => false,
                'empty_value' => 'Select',
                'attr' => array(
                    'disabled' => true,
                    'class' => 'teams_dd select_100px'
                    )
                )
            );
    }

    public function getName()
    {
        return 'divisionrole';
    }
}