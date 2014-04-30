<?php
namespace Ljms\GeneralBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    public function findAllThisFilter($data)
    {
       
    	$query = $this
    		->createQueryBuilder('t')
            ->select('t', 't.name  as team_name', 'd.name as division_name', 't.status', 't.id as id', 'l.name as league_name')
            ->leftJoin ('t.division_id', 'd')
            ->leftJoin ('t.league_type_id', 'l');
            if ($data) {
                if ($data['divisions']) {
                    $query->andWhere('t.division_id='.$data['divisions']);
                }
                if (strlen($data['league'])) {   
                    $query->andWhere('t.league_type_id='.$data['league']);
                }
                if (strlen($data['status'])) {   
                    $query->andWhere('t.status='.$data['status']);
                }
            }
            $query->getQuery();

        return $query;
    }
}

