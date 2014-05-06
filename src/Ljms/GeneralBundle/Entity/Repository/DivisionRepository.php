<?php
namespace Ljms\GeneralBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class DivisionRepository extends EntityRepository
{
    public function findAllThisFilter($data)
    {
    	$query = $this
    		->createQueryBuilder('d')
            ->select('d');
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

    public function countNumber()
    {       
        $qb = $this->createQueryBuilder('d');
        $qb->select('COUNT(d)');

        $limit_rows = $qb->getQuery();
        $limit_rows = $limit_rows->getResult();
        $limit_rows = $limit_rows[0][1];

            return $limit_rows;
    }

    public function divisionlist()
    {
        $qb = $this->createQueryBuilder('d');
    	$qb ->select('d.id')
    		->addSelect('d.name')
    		->add('orderBy', 'd.id ASC');

		$divisions = $qb->getQuery();
		$divisions = $divisions->getResult();

		 $divisions_list[''] = 'All';
        foreach ($divisions as $key => $value)
        {
            $divisions_list[$divisions[$key]['id']] = $divisions[$key]['name'];
        }
        return $divisions_list;
       
    }
}

