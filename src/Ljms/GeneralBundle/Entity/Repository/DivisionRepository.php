<?php
namespace Ljms\GeneralBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class DivisionRepository extends EntityRepository
{
    public function findAllThisFilter($data)
    {
       
    	$query = $this
    		->createQueryBuilder('d')
            ->Where('1 = 1')
            ->select('d');
            if ($data) {
                if ($data['divisions']) {
                    $query->andWhere('d.id='.$data['divisions']);
                }
                if (strlen($data['season'])) {   
                    $query->andWhere('d.fall_ball='.$data['season']);
                }
                if (strlen($data['status'])) {   
                    $query->andWhere('d.status='.$data['status']);
                }
            }
            $query->getQuery();

        return $query;
    }

    public function divisionlist()
    {
    	$query = $this
    		->createQueryBuilder('d')
    		->select('d.id')
    		->addSelect('d.name')
    		->add('orderBy', 'd.id ASC');
		$divisions = $query->getQuery();
		$divisions = $divisions->getResult();
		 $divisions_list[''] = 'All';
        foreach ($divisions as $key => $value) {
            $divisions_list[$divisions[$key]['id']] = $divisions[$key]['name'];
        }
        return $divisions_list;
       
    }
}

