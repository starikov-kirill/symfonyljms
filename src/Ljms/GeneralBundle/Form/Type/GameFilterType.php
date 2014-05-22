<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GameFilterType extends AbstractType
{
    // array for dropdown league
    private $leagueFilter = array(
        ''  => 'All',
        '1' => 'LJMS Teams',
        '2' => 'Non Conference Teams'
    );

    // array for dropdown league
    private $dateFilter = array(
        ''  => 'All',
        '1' => 'Future games',
        '2' => 'Prev game'
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $leagueFilter = $this->leagueFilter;
        $dateFilter = $this->dateFilter;
        
        $data = $builder->getData();
        $divisions= $data['divisionsList'];
        $teams= $data['teamsList'];

		$builder->setMethod('GET')
            ->add('divisions', 'choice', array(
                'choices'   => $divisions, 
                'empty_value' => 'All',
                'required'  => false, 
                'attr' => array(
                    'class' => 'select_100px')
                )
            )
            ->add('league', 'choice', array(
                'choices'   => $leagueFilter, 
                'required'  => false,
                'attr' => array(
                    'class' => 'select_100px'
                    )
                )
            )
            ->add('teams', 'choice', array(
                'choices' => $teams,
                'required'  => false, 
                'empty_value' => 'All',
                'attr' => array(
                    'class' => 'select_100px')
                )
            )
            ->add('date', 'choice', array(
                'choices' => $dateFilter,
                'required'  => false, 
                'attr' => array(
                    'class' => 'select_100px')
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
        return 'gamefilter';
    }
}