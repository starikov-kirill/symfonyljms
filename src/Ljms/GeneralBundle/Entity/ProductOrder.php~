<?php

namespace Ljms\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOrder
 */
class ProductOrder
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Ljms\GeneralBundle\Entity\Role
     */
    private $role;

    /**
     * @var \Ljms\GeneralBundle\Entity\User
     */
    private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role
     *
     * @param \Ljms\GeneralBundle\Entity\Role $role
     * @return ProductOrder
     */
    public function setRole(\Ljms\GeneralBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Ljms\GeneralBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \Ljms\GeneralBundle\Entity\User $user
     * @return ProductOrder
     */
    public function setUser(\Ljms\GeneralBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ljms\GeneralBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
