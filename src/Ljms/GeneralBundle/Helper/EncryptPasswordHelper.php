<?php

namespace Ljms\GeneralBundle\Helper;

class EncryptPasswordHelper {

 	private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function encryptPassword($user, $method)
    {
        $container  = $this->container;

        try
        {
        $factory = $container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        if ($method == 'edit')
        {
            $password = $encoder->encodePassword($user->getNewpassword(), $user->getSalt());
            $user->setNewpassword($password);
        } elseif ($method == 'add')
        {
            $password = $encoder->encodePassword($user->getpassword(), $user->getSalt());
        }
        $user->setPassword($password);

        return $password;

        } catch(Exception $e)
        {
            return new Response($e);
        } 

    }
}