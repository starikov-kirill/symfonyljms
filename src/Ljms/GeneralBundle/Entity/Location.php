<?php

namespace Ljms\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Location
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Location

{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Games", mappedBy="location")
     */
    protected $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add games
     *
     * @param \Ljms\GeneralBundle\Entity\Games $games
     * @return Location
     */
    public function addGame(\Ljms\GeneralBundle\Entity\Games $games)
    {
        $this->games[] = $games;

        return $this;
    }

    /**
     * Remove games
     *
     * @param \Ljms\GeneralBundle\Entity\Games $games
     */
    public function removeGame(\Ljms\GeneralBundle\Entity\Games $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get games
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }
}
