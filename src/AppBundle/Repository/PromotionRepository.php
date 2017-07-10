<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Promotion;
use Doctrine\ORM\EntityRepository;


/**
 * PromotionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PromotionRepository extends EntityRepository
{
    public function findAllActiveDESC()
    {
        $now = new \DateTime();
        return $this->createQueryBuilder("p")
            ->select("p")
            ->where("p.startsOn <= :date")
            ->andWhere("p.endsOn >= :date")
            ->setParameter("date", $now)
            ->orderBy("p.percentage", "DESC")
            ->getQuery()->getResult();
    }

    /**
     * @return Promotion[]
     */
    public function findAllNotExpiredDESC()
    {
        $now = new \DateTime();
        return $this->createQueryBuilder("p")
            ->select("p")
            ->where("p.endsOn >= :date")
            ->setParameter("date", $now)
            ->orderBy("p.percentage", "DESC")
            ->getQuery()->getResult();

    }
    public function findMaxPromotionForStock($stockId)
    {

        return $this->createQueryBuilder("pr")
            ->select("max(pr.percentage)")
            ->leftJoin("pr.stocks","s")
            ->where("pr.startsOn <= :now")
            ->andWhere("pr.endsOn >= :now")
            ->setParameter("now",new \DateTime())
            ->andWhere("s.quantity > 0")
            ->andWhere("s.isActive = true")
            ->andWhere("s.id = :stockId")
            ->setParameter("stockId", $stockId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findBiggestNotExpiredForStock($stockId)
    {
        return $this->createQueryBuilder("pr")
            ->select("max(pr.percentage)")
            ->leftJoin("pr.stocks","s")
            ->andWhere("pr.endsOn >= :now")
            ->setParameter("now",new \DateTime())
            ->andWhere("s.quantity > 0")
            ->andWhere("s.isActive = true")
            ->andWhere("s.id = :stockId")
            ->setParameter("stockId", $stockId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findMaxPromotionForProduct($productId)
    {
        return $this->createQueryBuilder("pr")
            ->select("max(pr.percentage)")
            ->leftJoin("pr.stocks","s")
            ->leftJoin("s.product","p")
            ->where("pr.startsOn <= :now")
            ->andWhere("pr.endsOn >= :now")
            ->setParameter("now",new \DateTime())
            ->andWhere("s.quantity > 0")
            ->andWhere("s.isActive = true")
            ->andWhere("s.id = :productId")
            ->setParameter("productId", $productId)
            ->getQuery()
            ->getSingleScalarResult();


    }
}
