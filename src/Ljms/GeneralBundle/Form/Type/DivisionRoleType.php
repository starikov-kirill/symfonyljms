<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DivisionRoleType extends AbstractType
{   
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ljms\GeneralBundle\Entity\Divisions',
        ));
    }

    public function getName()
    {
        return 'divisionrole';
    }
}

//delete
