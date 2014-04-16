<?php
	namespace Ljms\GeneralBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="divisions")
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
	     * @ORM\Column(type="string", length=100, nullable=true)
	     */
	    protected $logo;

	
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
     * @return Divisions
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
     * Set status
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
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set fall_ball
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
     * Get fall_ball
     *
     * @return boolean 
     */
    public function getFallBall()
    {
        return $this->fall_ball;
    }

    /**
     * Set age_from
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
     * Get age_from
     *
     * @return integer 
     */
    public function getAgeFrom()
    {
        return $this->age_from;
    }

    /**
     * Set age_to
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
     * Get age_to
     *
     * @return integer 
     */
    public function getAgeTo()
    {
        return $this->age_to;
    }

    /**
     * Set description
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
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set rules
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
     * Get rules
     *
     * @return string 
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Set base_fee
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
     * Get base_fee
     *
     * @return float 
     */
    public function getBaseFee()
    {
        return $this->base_fee;
    }

    /**
     * Set addon_fee
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
     * Get addon_fee
     *
     * @return float 
     */
    public function getAddonFee()
    {
        return $this->addon_fee;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Divisions
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
