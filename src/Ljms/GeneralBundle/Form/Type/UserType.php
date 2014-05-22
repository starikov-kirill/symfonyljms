<?php

namespace Ljms\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

		$builder->add('username', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide')
                    )
                )
                ->add('last_name', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide')
                    )
                )
                ->add('address', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide')
                    )
                )
                ->add('zipcode', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide')
                    )
                )
                ->add('city', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide')
                    )
                )
                ->add('alt_first_name', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide'),
                    'required'  => false)
                )
                ->add('alt_last_name', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide'),
                     'required'  => false)
                )
                ->add('home_phone', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide')
                    )
                )
                ->add('cell_phone', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide'),
                    'required'  => false)
                )
                ->add('alt_phone', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide'),
                    'required'  => false)
                )
                ->add('alt_phone_2', 'text', array(
                    'attr' => array(
                        'class' => 'select_wide'),
                    'required'  => false)
                )
                ->add('alt_email', 'email', array(
                    'attr' => array(
                        'class' => 'select_wide'),
                    'required'  => false)
                )
                ->add('states_id', 'entity', array(
                    'class' => 'LjmsGeneralBundle:States',
                    'empty_value' => 'Select',
                    'property' => 'name',
                    'attr' => array(
                        'class' => 'select_wide')
                    )
                )
                ->add('email', 'repeated', array(
                    'type' => 'email',
                    'invalid_message' => 'Emails do not match',
                    'options' => array(
                        'attr' => array(
                            'class' => 'select_wide')
                        )
                    )
                )
                ->add('save', 'submit', array(
                    'attr' => array(
                        'class' => 'button')
                    )
                )
                ->add('roles', 'entity', array(
                    'class' => 'LjmsGeneralBundle:Role',
                    'property' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                    'attr'=> array('style'=>'display:none')
                    )
                )
                ->add('teamsManagers', 'entity', array(
                    'class' => 'LjmsGeneralBundle:Teams',
                    'property' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                    'attr'=> array('style'=>'display:none')
                    )
                )
                ->add('teamsCoachs', 'entity', array(
                    'class' => 'LjmsGeneralBundle:Teams',
                    'property' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                    'attr'=> array('style'=>'display:none')
                    )
                )
                ->add('divisions', 'entity', array(
                    'class' => 'LjmsGeneralBundle:Divisions',
                    'property' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                    'attr'=> array('style'=>'display:none')
                    )
                );


        if ($options['block_name'] == 'updating')
        {
            $builder->add('newpassword', 'repeated', array(
                        'type' => 'password',
                        'required' => false,
                        'invalid_message' => 'Passwords do not match',
                        'options' => array(
                            'attr' => array(
                                'class' => 'select_wide',
                                )
                            )
                        )
                    );
        } elseif ($options['block_name'] == 'creating')
        {
            $builder->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Passwords do not match',
                    'options' => array(
                        'attr' => array(
                            'class' => 'select_wide',
                            )
                        )
                    )
                );
        }

    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ljms\GeneralBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'user';
    }

}
