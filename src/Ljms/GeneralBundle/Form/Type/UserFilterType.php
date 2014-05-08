<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $data = $builder->getData();

		$builder->setMethod('GET')
            ->add('divisions', 'choice', array(
                'choices'   => $data['divisionsList'],
                'required'  => false,
                'attr' => array(
                    'class' => 'select_wide'
                    )
                )
            )
            ->add('roles', 'choice', array(
                'choices' => $data['roleList'],
                'required'  => false,
                'attr' => array(
                    'class' => 'select_wide'
                    )
                )
            )
            ->add('filter', 'submit', array(
                'attr' => array(
                    'class' => 'button'
                    )
                )
            );
    }

    public function getName()
    {
        return 'divisionfilter';
    }
}