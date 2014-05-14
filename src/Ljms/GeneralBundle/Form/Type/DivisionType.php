<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DivisionType extends AbstractType
{   
        // array for dropdown age
        private $age = array(
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
            18 => 18
        );
        // array for dropdown status
        private $status = array(
            ''  => 'Select one',
            '1' => 'Active',
            '0' => 'Inactive'
        );
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $status = $this->status;
        $age = $this->age;

		$builder->add('status', 'choice', array(
                    'choices'   => $status, 
                    'attr' => array(
                        'class' => 'select_wide'
                        )
                    )
                )
    		    ->add('name', 'text')
    		    ->add('age_to', 'choice', array(
                    'choices'   => $age)
                )
    		    ->add('age_from', 'choice', array(
                    'choices'   => $age
                    )
                )
    		    ->add('description', 'textarea', array(
                    'attr' => array(
                         'cols' => '40', 
                         'rows' => '10'
                    ),
                    'required'=>false)
                )
    		    ->add('rules', 'textarea', array(
                    'attr' => array(
                        'cols' => '40',
                        'rows' => '10'
                        ), 
                    'required'=>false
                    )
                )
    		    ->add('base_fee', 'text', array(
                    'required'=> false
                    )
                )
    		    ->add('addon_fee', 'text', array(
                    'required'=> false
                    )
                )
                ->add('image', 'file', array(
                    'data_class' => null)
                )
    		    ->add('save', 'submit', array(
                    'attr' => array(
                        'class' => 'button'
                        )
                    )
                );
        if ($options['block_name'] == 'creating')
        {
            $builder->add('fall_ball', 'checkbox', array(
                'required'=> false
                )
            );
        }
    }

    public function getName()
    {
        return 'division';
    }
}
