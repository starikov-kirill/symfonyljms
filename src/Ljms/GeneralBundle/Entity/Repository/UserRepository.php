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

    public function massActionDelete($data)
    {
        $qb = $this->createQueryBuilder('u')
            ->delete('LjmsGeneralBundle:User', 'u')
            ->where('u.id IN (:id)')
            ->setParameter('id',  $data['ids'])            
            ->getQuery()
            ->execute(); 
            return $qb;       
       
    }

    public function massActionStatus($data)
    {
        $qb = $this->createQueryBuilder('u')
            ->update('LjmsGeneralBundle:User', 'u')
            ->where('u.id IN (:id)')
            ->setParameter('id',  $data['ids']) 
            ->set('u.isActive', '?1') 
            ->setParameter(1, $data['status'])          
            ->getQuery()
            ->execute(); 
            return $qb;       
       
    }

    public function getDivisionsWhereDirectorById($id)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id IN (:id)')
            ->setParameter('id', $id)
            ->leftJoin ('u.divisions', 'd')
            ->select('d.id')
            ->addselect('d.name');

        $roles = $qb->getQuery();
        $roles = $roles->getResult();         
 
        return $roles;       
       
    }

    public function getTeamsWhereCoachById($id)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id IN (:id)')
            ->setParameter('id', $id)
            ->leftJoin ('u.teamsCoachs', 'tc')
            ->select('tc.id')
            ->leftJoin('LjmsGeneralBundle:Teams', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.id = tc.id')
            ->leftJoin ('t.division_id', 'd')
            ->addselect('d.name as divisionName')
            ->addselect('tc.name');

        $roles = $qb->getQuery();
        $roles = $roles->getResult();         
 
        return $roles;       
       
    }

    public function getTeamsWhereManagerById($id)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id IN (:id)')
            ->setParameter('id', $id)
            ->leftJoin ('u.teamsManagers', 'tm')
            ->select('tm.id')
            ->leftJoin('LjmsGeneralBundle:Teams', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.id = tm.id')
            ->leftJoin ('t.division_id', 'd')
            ->addselect('d.name as divisionName')
            ->addselect('tm.name');

        $roles = $qb->getQuery();
        $roles = $roles->getResult();         
 
        return $roles;       
       
    }

    public function getAllDivisionsWhereDirectorAlreadyHas()
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id >(:id)')
            ->setParameter('id', 1)
            ->leftJoin ('u.divisions', 'd')
            ->select('d.id as divisionID')
            ->addselect('u.id as userID');

        $roles = $qb->getQuery();
        $roles = $roles->getResult(); 

     foreach ($roles as $key => $value)
        {
            if ($roles[$key]['divisionID'])
            {
                $divisionsList[$roles[$key]['divisionID']] = $roles[$key]['userID'];
            }
            
        }        
 
        return $divisionsList;       
       
    }

    public function getAllTeamsWhereCoachAlreadyHas()
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id >(:id)')
            ->setParameter('id', 1)
            ->leftJoin ('u.teamsCoachs', 'tc')
            ->select('tc.id as teamID')
            ->addselect('u.id as userID');

        $roles = $qb->getQuery();
        $roles = $roles->getResult(); 
       
        $teamsList = array();

        foreach ($roles as $key => $value)
        {
            if ($roles[$key]['teamID'])
            {
                $teamsList[$roles[$key]['teamID']] = $roles[$key]['userID'];
            }
            
        }        
 
        return $teamsList;       
       
    }
    public function getAllTeamsWhereManagerAlreadyHas()
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id >(:id)')
            ->setParameter('id', 1)
            ->leftJoin ('u.teamsManagers', 'tm')
            ->select('tm.id as teamID')
            ->addselect('u.id as userID');

        $roles = $qb->getQuery();
        $roles = $roles->getResult(); 
       
        $teamsList = array();

        foreach ($roles as $key => $value)
        {
            if ($roles[$key]['teamID'])
            {
                $teamsList[$roles[$key]['teamID']] = $roles[$key]['userID'];
            }
            
        }        
 
        return $teamsList;       
       
    }

}