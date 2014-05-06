<?php
	namespace Ljms\GeneralBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="teams")
     * @ORM\Entity(repositoryClass="Ljms\GeneralBundle\Entity\Repository\TeamRepository")
	 */
	class Teams	{
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
         * @ORM\Column(type="boolean")
         */
        protected $is_visitor;

        /**
         * @ORM\ManyToOne(targetEntity="Leagues", inversedBy="teams")
         * @ORM\JoinColumn(name="league_type_id", referencedColumnName="id")
         */
        protected $league_type_id;
        /**
         * @ORM\ManyToOne(targetEntity="Divisions", inversedBy="teams")
         * @ORM\JoinColumn(name="divisions_id", referencedColumnName="id")
         */
        protected $division_id;

	    

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
     * @return Teams
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
     * @return Teams
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
     * Set is_visitor
     *
     * @param boolean $isVisitor
     * @return Teams
     */
    public function setIsVisitor($isVisitor)
    {
        $this->is_visitor = $isVisitor;

        return $this;
    }

    /**
     * Get is_visitor
     *
     * @return boolean 
     */
    public function getIsVisitor()
    {
        return $this->is_visitor;
    }

    /**
     * Set league_type_id
     *
     * @param \Ljms\GeneralBundle\Entity\Leagues $leagueTypeId
     * @return Teams
     */
    public function setLeagueTypeId(\Ljms\GeneralBundle\Entity\Leagues $leagueTypeId = null)
    {
        $this->league_type_id = $leagueTypeId;

        return $this;
    }

    /**
     * Get league_type_id
     *
     * @return \Ljms\GeneralBundle\Entity\Leagues 
     */
    public function getLeagueTypeId()
    {
        return $this->league_type_id;
    }

    /**
     * Set division_id
     *
     * @param \Ljms\GeneralBundle\Entity\Divisions $divisionId
     * @return Teams
     */
    public function setDivisionId(\Ljms\GeneralBundle\Entity\Divisions $divisionId = null)
    {
        $this->division_id = $divisionId;

        return $this;
    }

    /**
     * Get division_id
     *
     * @return \Ljms\GeneralBundle\Entity\Divisions 
     */
    public function getDivisionId()
    {
        return $this->division_id;
    }
}
