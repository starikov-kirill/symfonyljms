<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TeamFilterType extends AbstractType
{
    // array for dropdown league
    private $leagueFilter = array(
        ''  => 'All',
        '1' => 'LJMS Teams',
        '2' => 'Non Conference Teams'
    );

    // array for dropdown status
    private $statusFilter = array(
        ''  => 'All',
        '1' => 'Active',
        '0' => 'Inactive'
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $statusFilter = $this->statusFilter;
        $leagueFilter = $this->leagueFilter;
        
        $data = $builder->getData();

		$builder->setMethod('GET')
            ->add('divisions', 'choice', array(
                'choices'  => $data,
                'required' => false,
                'attr' => array(
                    'class' => 'select_wide'
                    )
                )
            )
            ->add('status', 'choice', array(
                'choices'  => $statusFilter,
                'required' => false,
                'attr'     => array(
                    'class' => 'select_wide'
                    )
                )
            )
            ->add('league', 'choice', array(
                'choices' => $leagueFilter,
                'required'  => false,
                'attr' => array(
                    'class' => 'select_wide'
                    )
                )
            )
            ->add('filter', 'submit', array(
                'attr' => array(
                    'class' => 'button')
                )
            );
    }

    public function getName()
    {
        return 'teamfilter';
    }
}