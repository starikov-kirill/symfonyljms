<?php
namespace Ljms\GeneralBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class DivisionRepository extends EntityRepository
{
    public function findAllThisFilter($data)
    {
    	$query = $this
    		->createQueryBuilder('d')
            ->select('d',
                     't',
                     'GROUP_CONCAT(t.name) as teams',
                     'd.name as division_name',
                     'd.status as status',
                     'd.fall_ball as fallball',
                     'd.id as id'
                     )

            ->leftJoin('d.teams', 't', 'WITH', 't.division_id = d.id')
            ->groupBy('d.id');
            if ($data) 
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
            }
            $query->getQuery();

        return $query;
    }

    public function getCountNumberDivisions()
    {       
        $qb = $this->createQueryBuilder('d');
        $qb->select('COUNT(d)');

        $limitRows = $qb->getQuery();
        $limitRows = $limitRows->getResult();
        $limitRows = $limitRows[0][1];

        return $limitRows;
    }

    public function findAllDevisions()
    {
        $qb = $this->createQueryBuilder('d');
    	$qb ->select('d.id')
    		->addSelect('d.name')
    		->add('orderBy', 'd.id ASC');

		$divisions = $qb->getQuery();
		$divisions = $divisions->getResult();

		$divisionsList[''] = 'All';

        foreach ($divisions as $key => $value)
        {
            $divisionsList[$divisions[$key]['id']] = $divisions[$key]['name'];
        }

        return $divisionsList;
       
    }
}