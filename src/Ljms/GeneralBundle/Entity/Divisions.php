<?php
namespace Ljms\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="divisions")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Ljms\GeneralBundle\Entity\Repository\DivisionRepository")
 */
class Divisions	{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $fall_ball;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    protected $age_from;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    protected $age_to;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $rules;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $base_fee;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $addon_fee;

    /**
     * @var string $image`
     * @ORM\Column(name="image", type="string", length=255)
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Teams", mappedBy="division_id", cascade={"all"})
     */
    protected $teams;


    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }
	
    /**
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $name
     * @return Divisions
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param boolean $status
     * @return Divisions
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @param boolean $fallBall
     * @return Divisions
     */
    public function setFallBall($fallBall)
    {
        $this->fall_ball = $fallBall;

        return $this;
    }

    /**
     *
     * @return boolean 
     */
    public function getFallBall()
    {
        return $this->fall_ball;
    }

    /**
     *
     * @param integer $ageFrom
     * @return Divisions
     */
    public function setAgeFrom($ageFrom)
    {
        $this->age_from = $ageFrom;

        return $this;
    }

    /**
     *
     * @return integer 
     */
    public function getAgeFrom()
    {
        return $this->age_from;
    }

    /**
     *
     * @param integer $ageTo
     * @return Divisions
     */
    public function setAgeTo($ageTo)
    {
        $this->age_to = $ageTo;

        return $this;
    }

    /**
     *
     * @return integer 
     */
    public function getAgeTo()
    {
        return $this->age_to;
    }

    /**
     *
     * @param string $description
     * @return Divisions
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $rules
     * @return Divisions
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     *
     * @return string 
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     *
     * @param float $baseFee
     * @return Divisions
     */
    public function setBaseFee($baseFee)
    {
        $this->base_fee = $baseFee;

        return $this;
    }

    /**
     *
     * @return float 
     */
    public function getBaseFee()
    {
        return $this->base_fee;
    }

    /**
     * @param string $image
     * @return Divisions
     */
    public function setImage($image)
    {
        if($image !== null) {
            $this->image = $image;

            return $this;
        } 
    }
 
    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     *
     * @param float $addonFee
     * @return Divisions
     */
    public function setAddonFee($addonFee)
    {
        $this->addon_fee = $addonFee;

        return $this;
    }

    /**
     *
     * @return float 
     */
    public function getAddonFee()
    {
        return $this->addon_fee;
    }


    public function isAge_check()
    {
        return ($this->age_from < $this->age_to);
    }

    /**
     *
     * @param \Ljms\GeneralBundle\Entity\Teams $teams
     * @return Divisions
     */
    public function addTeam(\Ljms\GeneralBundle\Entity\Teams $teams)
    {
        $this->teams[] = $teams;

        return $this;
    }

    /**
     *
     * @param \Ljms\GeneralBundle\Entity\Teams $teams
     */
    public function removeTeam(\Ljms\GeneralBundle\Entity\Teams $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }
    public function getFullImagePath() 
    {
        return null === $this->image ? null : $this->getUploadRootDir(). $this->image;
    }
 
    protected function getTmpUploadRootDir(){
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web/upload/';
    }
 
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadImage() {
        // if file upload $pos >= 0
        $pos = strpos($this->image, '/');

        if ($pos === false)
        {
            return;
        } else 
        {
            $rnd = mt_rand();
            $format = substr($this->image->getClientOriginalName(), strrpos($this->image->getClientOriginalName(), '.'));
            $name = $rnd.$format;

            $this->image->move($this->getTmpUploadRootDir(), $name);

            $this->setImage($name);
        }
    }
}
