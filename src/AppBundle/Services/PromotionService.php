<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/20/2017
 * Time: 1:14 AM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Stock;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\DateTime;

class PromotionService
{

    /**
     * @param $stock Stock
     * @return Promotion|null
     */
    public function findMaxPromotionForStock($stock)
    {
        $maxPromotion=null;
        /** @var Promotion[]|ArrayCollection $promotions */
        $promotions = $stock->getPromotions()->toArray();
        usort(
            $promotions,function ($a, $b){
            return $b->compareTo($a);
        });
        foreach ($promotions as $promotion) {

            $now = new \DateTime("now");
            if($promotion->getStartsOn()<=$now && $promotion->getEndsOn()>=$now){

                $maxPromotion =$promotion;
                break;
            }
        }
        return $maxPromotion;
    }
    public function findEffectivePromotionForOrder($order)
    {
        $promotions =$order->getStock()->getPromotions()->toArray();
        $effectivePromotion = null;
        usort(
            $promotions,function ($a, $b){
            return $b->compareTo($a);
        });
        foreach ($promotions as $promotion) {
            $then = $order->getAddedOn();
            if($promotion->getStartsOn()<=$then && $promotion->getEndsOn()>=$then){
                $effectivePromotion = $promotion;
                break;

            }
        }
        return $effectivePromotion;
    }

    /**
     * @param $product Product
     */
    public function    findMaxPromotionForProduct($product)
    {

        $maxPromotion = null;
        foreach ($product->getStocks() as $stock)
        {
            $potentialMax = $this->findMaxPromotionForStock($stock);
            if($potentialMax)
            {
                if($maxPromotion==null||($potentialMax->getPercentage()>$maxPromotion->getPercentage())){
                    $maxPromotion=$potentialMax;
                }
            }
        }
        return $maxPromotion;
    }

}