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
            ->leftJoin("p.stocks","s")
            ->where("s.quantity > 0")
            ->andwhere("p.name LIKE :terms")
            ->orWhere("p.description LIKE :terms")
            ->setParameter("terms",$terms)
            ->getQuery()
            ->getResult();
    }
    public function getAllActiveProducts($categoryId)
    {
       return $this->createQueryBuilder("p")
            ->select("p")
            ->leftJoin("p.stocks","s")
            ->where("s.quantity > 0")
            ->andWhere("s.isActive = true")
            ->andWhere("p.category = :categoryId")
            ->setParameter("categoryId",$categoryId)
            ->getQuery()
           ->getResult();
    }
}
