<?php
namespace Ljms\GeneralBundle\Entity\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin LjmsGeneralBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }
    public function findAllThisFilter($data)
    {
        $query = $this
            ->createQueryBuilder('u')
            ->select('u',
                     'u.username',
                     'u.last_name',
                     'u.isActive',
                     'u.home_phone as phone',
                     'u.id as id',
                     'u.email'
                     )
            ->groupBy('u.id');
            /*if ($data) 
            {
                if (isset($data['divisions'])) 
                {
                    $query->andWhere('d.id='.$data['divisions']);
                }

                if (isset($data['season']) && strlen($data['season'])) 
                {   
                    $query->andWhere('d.fall_ball='.$data['season']);
                }

                if (isset($data['status']) && strlen($data['status']))
                {   
                    $query->andWhere('d.status='.$data['status']);
                }
            }*/
            $query->getQuery();

        return $query;
    }

    public function getCountNumberUsers()
    {       
        $qb = $this->createQueryBuilder('u');
        $qb->select('COUNT(u)');

        $limitRows = $qb->getQuery();
        $limitRows = $limitRows->getResult();
        $limitRows = $limitRows[0][1];

        return $limitRows;
    }

    public function emailJqCheck($email)
    {       
        $qb = $this->createQueryBuilder('u');
        $qb ->select('u.id')
            ->where('u.email = :email')
            ->setParameter('email', $email);
            
        $id = $qb->getQuery();
        $id = $id->getResult();

        foreach ($id as $key => $value)
        {
            $id = $id[$key]['id'];
        }
        
        if (count($id) == 0)
        {
            $id = 'no';
        } 

        return $id;
    }
}
