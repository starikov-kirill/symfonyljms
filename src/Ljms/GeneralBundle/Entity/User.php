<?php
    namespace Ljms\GeneralBundle\Entity;

    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Security\Core\User\AdvancedUserInterface;


    /**
     * Ljms\GeneralBundle\Entity\User
     * @ORM\Entity
     * @ORM\Table(name="users")
     * @ORM\Entity(repositoryClass="Ljms\GeneralBundle\Entity\UserRepository")
     * @UniqueEntity(fields="email", message="Sorry, this email address is already in use.")
     */
    class User implements AdvancedUserInterface, \Serializable {

        protected $em;
        public function setEm($em) {
            $this->em = $em;
        }
        /**
         * @ORM\Column(type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=25)
         */
        private $username;

        /**
         * @ORM\Column(type="string", length=64)
         */
        private $password;
        
        /**
         * @ORM\Column(type="string", length=25)
         */
        private $last_name;

        /**
         * @ORM\Column(type="string", length=25)
         */
        private $address;

        /**
         * @ORM\Column(type="string", length=25)
         */
        private $city;

        /**
         * @ORM\Column(type="string", length=25, nullable=true)
         */
        private $alt_first_name;

        /**
         * @ORM\Column(type="string", length=25, nullable=true)
         */
        private $alt_last_name;

        /**
         * @ORM\Column(type="bigint", length=25)
         */
        private $home_phone;

        /**
         * @ORM\Column(type="bigint", length=25, nullable=true)
         */
        private $cell_phone;

        /**
         * @ORM\Column(type="bigint", length=25, nullable=true)
         */
        private $alt_phone;

        /**
         * @ORM\Column(type="bigint", length=25, nullable=true)
         */
        private $alt_phone_2;

        /**
         * @ORM\Column(type="string", length=60, nullable=true)
         */
        private $alt_email;

        /**
         * @var string $email
         * @ORM\Column(type="string", length=60, unique=true)
         */
        private $email;

        /**
         * @ORM\Column(name="is_active", type="boolean")
         */
        private $isActive;

        /**
         * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
         *
         */
        private $roles;
        /**
         * @ORM\ManyToOne(targetEntity="States", inversedBy="user")
         * @ORM\JoinColumn(name="states_id", referencedColumnName="id")
         */
        protected $states_id;
        
        /**
         * @ORM\Column(type="bigint", length=10)
         */
        private $zipcode;


        public function __construct()
        {
    		$this->roles = new ArrayCollection();
        }

        /**
         * @inheritDoc
         */
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * @inheritDoc
         */
        public function getSalt()
        {
            // you *may* need a real salt depending on your encoder
            // see section on salt below
            return null;
        }

        /**
         * @inheritDoc
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * @inheritDoc
         */
        public function getRoles()
        {
            return $this->roles->toArray();
        }

        /**
         * @inheritDoc
         */
        public function eraseCredentials()
        {
        }

        /**
         * @see \Serializable::serialize()
         */
        public function serialize()
        {
            return serialize(array(
                $this->id,
                $this->username,
                $this->password,
                // see section on salt below
                // $this->salt,
            ));
        }

        /**
         * @see \Serializable::unserialize()
         */
        public function unserialize($serialized)
        {
            list (
                $this->id,
                $this->username,
                $this->password,
                // see section on salt below
                // $this->salt
            ) = unserialize($serialized);
        }
        public function isAccountNonExpired()
        {
            return true;
        }

        public function isAccountNonLocked()
        {
            return true;
        }

        public function isCredentialsNonExpired()
        {
            return true;
        }

        public function isEnabled()
        {
            return $this->isActive;
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
         * Set username
         *
         * @param string $username
         * @return User
         */
        public function setUsername($username)
        {
            $this->username = $username;

            return $this;
        }

        /**
         * Set password
         *
         * @param string $password
         * @return User
         */
        public function setPassword($password)
        {
            $this->password = $password;

            return $this;
        }

        /**
         * Set email
         *
         * @param string $email
         * @return User
         */
        public function setEmail($email)
        {
            $this->email = $email;

            return $this;
        }

        /**
         * Get email
         *
         * @return string 
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set isActive
         *
         * @param boolean $isActive
         * @return User
         */
        public function setIsActive($isActive)
        {
            $this->isActive = $isActive;

            return $this;
        }

        /**
         * Get isActive
         *
         * @return boolean 
         */
        public function getIsActive()
        {
            return $this->isActive;
        }

        /**
         * Add roles
         *
         * @param \Ljms\GeneralBundle\Entity\Role $roles
         * @return User
         */
        public function addRole(\Ljms\GeneralBundle\Entity\Role $roles)
        {
            $this->roles[] = $roles;

            return $this;
        }

        /**
         * Remove roles
         *
         * @param \Ljms\GeneralBundle\Entity\Role $roles
         */
        public function removeRole(\Ljms\GeneralBundle\Entity\Role $roles)
        {
            $this->roles->removeElement($roles);
        }

        /**
         * Set last_name
         *
         * @param string $lastName
         * @return User
         */
        public function setLastName($lastName)
        {
            $this->last_name = $lastName;

            return $this;
        }

        /**
         * Get last_name
         *
         * @return string 
         */
        public function getLastName()
        {
            return $this->last_name;
        }

        /**
         * Set address
         *
         * @param string $address
         * @return User
         */
        public function setAddress($address)
        {
            $this->address = $address;

            return $this;
        }

        /**
         * Get address
         *
         * @return string 
         */
        public function getAddress()
        {
            return $this->address;
        }

        /**
         * Set city
         *
         * @param string $city
         * @return User
         */
        public function setCity($city)
        {
            $this->city = $city;

            return $this;
        }

        /**
         * Get city
         *
         * @return string 
         */
        public function getCity()
        {
            return $this->city;
        }

        /**
         * Set alt_first_name
         *
         * @param string $altFirstName
         * @return User
         */
        public function setAltFirstName($altFirstName)
        {
            $this->alt_first_name = $altFirstName;

            return $this;
        }

        /**
         * Get alt_first_name
         *
         * @return string 
         */
        public function getAltFirstName()
        {
            return $this->alt_first_name;
        }

        /**
         * Set alt_last_name
         *
         * @param string $altLastName
         * @return User
         */
        public function setAltLastName($altLastName)
        {
            $this->alt_last_name = $altLastName;

            return $this;
        }

        /**
         * Get alt_last_name
         *
         * @return string 
         */
        public function getAltLastName()
        {
            return $this->alt_last_name;
        }

        /**
         * Set home_phone
         *
         * @param integer $homePhone
         * @return User
         */
        public function setHomePhone($homePhone)
        {
            $this->home_phone = $homePhone;

            return $this;
        }

        /**
         * Get home_phone
         *
         * @return integer 
         */
        public function getHomePhone()
        {
            return $this->home_phone;
        }

        /**
         * Set cell_phone
         *
         * @param integer $cellPhone
         * @return User
         */
        public function setCellPhone($cellPhone)
        {
            $this->cell_phone = $cellPhone;

            return $this;
        }

        /**
         * Get cell_phone
         *
         * @return integer 
         */
        public function getCellPhone()
        {
            return $this->cell_phone;
        }

        /**
         * Set alt_phone
         *
         * @param integer $altPhone
         * @return User
         */
        public function setAltPhone($altPhone)
        {
            $this->alt_phone = $altPhone;

            return $this;
        }

        /**
         * Get alt_phone
         *
         * @return integer 
         */
        public function getAltPhone()
        {
            return $this->alt_phone;
        }

        /**
         * Set alt_phone_2
         *
         * @param integer $altPhone2
         * @return User
         */
        public function setAltPhone2($altPhone2)
        {
            $this->alt_phone_2 = $altPhone2;

            return $this;
        }

        /**
         * Get alt_phone_2
         *
         * @return integer 
         */
        public function getAltPhone2()
        {
            return $this->alt_phone_2;
        }

        /**
         * Set alt_email
         *
         * @param string $altEmail
         * @return User
         */
        public function setAltEmail($altEmail)
        {
            $this->alt_email = $altEmail;

            return $this;
        }

        /**
         * Get alt_email
         *
         * @return string 
         */
        public function getAltEmail()
        {
            return $this->alt_email;
        }
    
    /**
     * Set states_id
     *
     * @param \Ljms\GeneralBundle\Entity\States $statesId
     * @return User
     */
    public function setStatesId(\Ljms\GeneralBundle\Entity\States $statesId = null)
    {
        $this->states_id = $statesId;

        return $this;
    }

    /**
     * Get states_id
     *
     * @return \Ljms\GeneralBundle\Entity\States 
     */
    public function getStatesId()
    {
        return $this->states_id;
    }

    /**
     * Set zipcode
     *
     * @param integer $zipcode
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return integer 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

}
