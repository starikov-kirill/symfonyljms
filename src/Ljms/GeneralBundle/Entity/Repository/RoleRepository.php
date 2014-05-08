<?php
namespace Ljms\GeneralBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{
    public function findAllRoles()
    {
        $qb = $this->createQueryBuilder('r');
    	$qb ->select('r.id')
    		->addSelect('r.name')
    		->add('orderBy', 'r.id ASC');

		$roles = $qb->getQuery();
		$roles = $roles->getResult();

		$rolesList[''] = 'All';

        foreach ($roles as $key => $value)
        {
            $rolesList[$roles[$key]['id']] = $roles[$key]['name'];
        }

        return $rolesList;
       
    }
}
