<?php
namespace Ljms\GeneralBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
	 public function findAllThisFilter($data)
    {

    	$query = $this
    		->createQueryBuilder('g')
            ->select('g',
            	'g.date',
            	'g.time',
            	'g.practice',
            	'g.id as id',
            	'd.name as division',
            	'l.name as location',
                'g.status as status'
                //'ht.name as home_team',
               // 'vt.name as visitor_team'
                )
            ->leftJoin('g.division_id', 'd')
            ->leftJoin ('g.location_id', 'l')
            //->leftJoin ('t.home_team_id', 'ht')
            //->leftJoin ('t.visitor_team_id', 'vt')
            ->groupBy('g.id');



           /* if ($data) 
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

    public function getCountNumberGames()
    {       
        $qb = $this->createQueryBuilder('g');
        $qb->select('COUNT(g)');

        $limitRows = $qb->getQuery();
        $limitRows = $limitRows->getResult();
        $limitRows = $limitRows[0][1];

        return $limitRows;
    }
}