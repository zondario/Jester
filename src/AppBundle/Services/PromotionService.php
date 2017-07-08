<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/20/2017
 * Time: 1:14 AM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Product;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Stock;
use AppBundle\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;


class PromotionService
{
    private $em;
    /**
     * PromotionService constructor.
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $stock Stock
     * @return Promotion|null
     */
    public function findMaxPromotionForStock($stock)
    {
         /** @var PromotionRepository $repo */
            $repo = $this->em->getRepository(Promotion::class);
        return $repo->findMaxPromotionForStock($stock->getId());
    }

    /**
     * @param $order ProductOrder
     * @return null|Promotion
     */
    public function findEffectivePromotionForOrder($order)
    {
        /** @var Promotion[] $promotions */
        $promotions = $order->getStock()->getPromotions()->toArray();
        $effectivePromotion = null;
        usort(
            $promotions, function (Promotion $a,Promotion $b) {
            return $b->compareTo($a);
        });
        foreach ($promotions as $promotion) {
            $then = $order->getAddedOn();
            if ($promotion->getStartsOn() <= $then && $promotion->getEndsOn() >= $then) {
                $effectivePromotion = $promotion;
                break;

            }
        }
        return $effectivePromotion;
    }

    /**
     * @param $product Product
     * @return Promotion
     */
    public function findMaxPromotionForProduct($product)
    {

        /** @var Promotion|null $maxPromotion */
        $maxPromotion = null;
        foreach ($product->getStocks() as $stock) {
           if($stock->isIsActive() && $stock->getQuantity()  >  0){
               $potentialMax = $this->findMaxPromotionForStock($stock);
               if ($potentialMax) {
                   if ($maxPromotion == null || ($potentialMax->getPercentage() > $maxPromotion->getPercentage())) {
                       $maxPromotion = $potentialMax;
                   }
               }
           }
        }
        return $maxPromotion;
    }

    /**
     * @param $stock Stock
     * @return Promotion
     *
     *
     */
    public function findBiggestNotExpiredForStock($stock)
    {
        $promotionToShow = null;
        $now = new \DateTime();
        $stockPromotions= $stock->getPromotions()->toArray();
        usort( $stockPromotions,function (Promotion $a, Promotion $b){return $b->compareTo($a);});
        foreach ($stock->getPromotions() as $promotion) {
            if($promotion->getEndsOn()>=$now)
            {
                $promotionToShow = $promotion;
            }
        }
        return $promotionToShow;
    }
}