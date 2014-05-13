<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MassActionType extends AbstractType
{
    // array for dropdown action
    private $action = array(
        ''  => 'Select',
        'delete'    => 'Delete',
        'active' => 'Active',
        'inactive' => 'Inactive',
    );


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $action = $this->action;

        $data = $builder->getData();
        $url = $data['url'];
        
		$builder->setMethod('POST')
            ->setAction($url.'/mass_action')
            ->add('action', 'choice', array(
                'choices'  => $action,
                'required' => false,
                'attr' => array(
                    'class' => 'action_dropdown'
                    )
                )
            );
    }

    public function getName()
    {
        return 'action';
    }
}