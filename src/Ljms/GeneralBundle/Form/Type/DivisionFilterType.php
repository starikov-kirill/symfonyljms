<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DivisionFilterType extends AbstractType
{

    private $status_filter = array(''  => 'All', '1'    => 'Active', '0' => 'Inactive');

    private $season_filter = array(''  => 'All', '0'    => 'Standart', '1' => 'Fall Ball');

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $status_filter = $this->status_filter;
        $season_filter = $this->season_filter;
        $data = $builder->getData();

		$builder->setMethod('GET')
            ->add('divisions', 'choice', array(
                'choices'   => $data, 'required'  => false, 'attr' => array(
                    'class' => 'select_wide')
                )
            )
            ->add('status', 'choice', array(
                'choices'   => $status_filter, 'required'  => false, 'attr' => array(
                    'class' => 'select_wide'
                    )
                )
            )
            ->add('season', 'choice', array(
                'choices' => $season_filter, 'required'  => false, 'attr' => array(
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