<?php

namespace Ljms\GeneralBundle\Helper;

class EncryptPasswordHelper {

 	private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function encryptPassword($user)
    {
        $container  = $this->container;

        try
        {
        $factory = $container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);

        return $password;

        } catch(Exception $e)
        {
            return new Response($e);
        } 

    }
}