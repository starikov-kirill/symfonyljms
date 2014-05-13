<?php
namespace Ljms\GeneralBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    public function findAllThisFilter($data)
    {       
    	$query = $this
    		->createQueryBuilder('t')
            ->select('t', 't.name  as team_name', 
                     'd.name as division_name',
                     't.status', 
                     't.id as id',
                     'l.name as league_name'
                    )
            ->leftJoin ('t.division_id', 'd')
            ->leftJoin ('t.league_type_id', 'l');
            if ($data) {
                if (isset($data['divisions'])) 
                {
                    $query->andWhere('t.division_id='.$data['divisions']);
                }
                if (isset($data['league']) && strlen($data['league']))
                {   
                    $query->andWhere('t.league_type_id='.$data['league']);
                }
                if (isset($data['status']) && strlen($data['status']))
                {   
                    $query->andWhere('t.status='.$data['status']);
                }
            }
            $query->getQuery();

        return $query;
    }

    public function getCountNumberTeams()
    {       
        $qb = $this->createQueryBuilder('t');
        $qb->select('COUNT(t)');

        $limitRows = $qb->getQuery();
        $limitRows = $limitRows->getResult();
        $limitRows = $limitRows[0][1];

        return $limitRows;
    }

    public function massActionDelete($data)
    {
        $qb = $this->createQueryBuilder('t')
            ->delete('LjmsGeneralBundle:Teams', 't')
            ->where('t.id IN (:id)')
            ->setParameter('id',  $data['ids'])            
            ->getQuery()
            ->execute(); 
            return $qb;       
       
    }

    public function massActionStatus($data)
    {
        $qb = $this->createQueryBuilder('t')
            ->update('LjmsGeneralBundle:Teams', 't')
            ->where('t.id IN (:id)')
            ->setParameter('id',  $data['ids']) 
            ->set('t.status', '?1') 
            ->setParameter(1, $data['status'])          
            ->getQuery()
            ->execute(); 
            return $qb;       
       
    }
}

