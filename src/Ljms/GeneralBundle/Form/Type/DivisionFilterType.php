<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DivisionFilterType extends AbstractType
{
    // array for dropdown status
    private $statusFilter = array(
        ''  => 'All',
        '1' => 'Active',
        '0' => 'Inactive'
    );
    // array for dropdown season
    private $seasonFilter = array(
        ''  => 'All',
        '0' => 'Standart',
        '1' => 'Fall Ball'
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $statusFilter = $this->statusFilter;
        $seasonFilter = $this->seasonFilter;
        
        $data = $builder->getData();

		$builder->setMethod('GET')
            ->add('divisions', 'choice', array(
                'choices'   => $data, 
                'required'  => false, 
                'attr' => array(
                    'class' => 'select_wide')
                )
            )
            ->add('status', 'choice', array(
                'choices'   => $statusFilter, 
                'required'  => false,
                'attr' => array(
                    'class' => 'select_wide'
                    )
                )
            )
            ->add('season', 'choice', array(
                'choices' => $seasonFilter,
                'required'  => false, 
                'attr' => array(
                    'class' => 'select_wide')
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
        return 'divisionfilter';
    }
}