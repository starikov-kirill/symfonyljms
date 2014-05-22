<?php
namespace Ljms\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="games")
  * @ORM\Entity(repositoryClass="Ljms\GeneralBundle\Entity\Repository\GameRepository")
 */
class Games	{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @ORM\Column(type="time")
     */
    protected $time;

    /**
     * @ORM\ManyToOne(targetEntity="Divisions", inversedBy="games")
     * @ORM\JoinColumn(name="divisions_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $division_id;

    /**
     * @ORM\ManyToOne(targetEntity="Teams", inversedBy="games")
     * @ORM\JoinColumn(name="home_team_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $home_team_id;

    /**
     * @ORM\ManyToOne(targetEntity="Teams", inversedBy="games")
     * @ORM\JoinColumn(name="visitor_team_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $visitor_team_id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $practice;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="games")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location_id;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    protected $home_team_result;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    protected $visitor_team_result;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $status;




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
     * Set date
     *
     * @param \DateTime $date
     * @return Games
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Games
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set practice
     *
     * @param boolean $practice
     * @return Games
     */
    public function setPractice($practice)
    {
        $this->practice = $practice;

        return $this;
    }

    /**
     * Get practice
     *
     * @return boolean 
     */
    public function getPractice()
    {
        return $this->practice;
    }

    /**
     * Set home_team_result
     *
     * @param integer $homeTeamResult
     * @return Games
     */
    public function setHomeTeamResult($homeTeamResult)
    {
        $this->home_team_result = $homeTeamResult;

        return $this;
    }

    /**
     * Get home_team_result
     *
     * @return integer 
     */
    public function getHomeTeamResult()
    {
        return $this->home_team_result;
    }

    /**
     * Set visitor_team_result
     *
     * @param integer $visitorTeamResult
     * @return Games
     */
    public function setVisitorTeamResult($visitorTeamResult)
    {
        $this->visitor_team_result = $visitorTeamResult;

        return $this;
    }

    /**
     * Get visitor_team_result
     *
     * @return integer 
     */
    public function getVisitorTeamResult()
    {
        return $this->visitor_team_result;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Games
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
     * Set division_id
     *
     * @param \Ljms\GeneralBundle\Entity\Divisions $divisionId
     * @return Games
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

    /**
     * Set home_team_id
     *
     * @param \Ljms\GeneralBundle\Entity\Teams $homeTeamId
     * @return Games
     */
    public function setHomeTeamId(\Ljms\GeneralBundle\Entity\Teams $homeTeamId = null)
    {
        $this->home_team_id = $homeTeamId;

        return $this;
    }

    /**
     * Get home_team_id
     *
     * @return \Ljms\GeneralBundle\Entity\Teams 
     */
    public function getHomeTeamId()
    {
        return $this->home_team_id;
    }

    /**
     * Set visitor_team_id
     *
     * @param \Ljms\GeneralBundle\Entity\Teams $visitorTeamId
     * @return Games
     */
    public function setVisitorTeamId(\Ljms\GeneralBundle\Entity\Teams $visitorTeamId = null)
    {
        $this->visitor_team_id = $visitorTeamId;

        return $this;
    }

    /**
     * Get visitor_team_id
     *
     * @return \Ljms\GeneralBundle\Entity\Teams 
     */
    public function getVisitorTeamId()
    {
        return $this->visitor_team_id;
    }

    /**
     * Set location_id
     *
     * @param \Ljms\GeneralBundle\Entity\Location $locationId
     * @return Games
     */
    public function setLocationId(\Ljms\GeneralBundle\Entity\Location $locationId = null)
    {
        $this->location_id = $locationId;

        return $this;
    }

    /**
     * Get location_id
     *
     * @return \Ljms\GeneralBundle\Entity\Location 
     */
    public function getLocationId()
    {
        return $this->location_id;
    }
}
