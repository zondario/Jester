<?php

namespace AppBundle\Repository;


/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
use Doctrine\ORM\EntityRepository;
class ProductRepository extends EntityRepository
{
    public function getBySearchTerms($terms)
    {
       return $this->createQueryBuilder("p")
            ->select("p")
            ->where("p.name LIKE :terms")
            ->orWhere("p.description LIKE :terms")
            ->setParameter("terms",$terms)
            ->getQuery()
            ->getResult();
    }
}
