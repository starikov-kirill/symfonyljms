<?php
    namespace Ljms\GeneralBundle\Entity;

    class Auth
    {
        protected $Email;

        protected $Password;

        public function getEmail()
        {
            return $this->Email;
        }

        public function setEmail($Email)
        {
            $this->auth = $Email;
        }

        public function getPassword()
        {
            return $this->Password;
        }

        public function setPassword($Password)
        {
            $this->auth = $Password;
        }
    }