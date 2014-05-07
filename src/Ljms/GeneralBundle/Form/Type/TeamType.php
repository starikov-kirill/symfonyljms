<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $status = $this->status;
        $visitor = $this->visitor;

		$builder->add('status', 'choice', array(
                    'choices'   => $status, 
                    'attr' => array(
                        'class' => 'select_wide'
                        )
                    )
                )
            ->add('is_visitor', 'choice', array(
                    'choices'   => $visitor, 
                    'attr' => array(
                        'class' => 'select_wide'
                        )
                    )
            )
            ->add('division_id', 'entity', array(
                    'class' => 'LjmsGeneralBundle:Divisions', 
                    'property' => 'name',
                    'attr' => array(
                        'class' => 'select_wide'
                        )
                    )
            )
            ->add('league_type_id', 'entity', array(
                    'class' => 'LjmsGeneralBundle:Leagues', 
                    'property' => 'name', 
                    'attr' => array(
                        'class' => 'select_wide'
                        )
                    )
            )
            ->add('name', 'text',  array(
                    'attr' => array(
                        'class' => 'select_wide'
                        )
                    )
            )
            ->add('save', 'submit', array(
                    'attr' => array(
                        'class' => 'button'
                        )
                    )
            );
    }

    public function getName()
    {
        return 'team';
    }

    private $visitor = array(
        '0' => 'No', 
        '1' => 'Yes'
        );

    private $status = array(
        ''  => 'Select one',
        '1' => 'Active',
        '0' => 'Inactive'
        );
}
