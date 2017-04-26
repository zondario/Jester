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
use Doctrine\Common\Persistence\ObjectManager;


class ArrayAggregatorService
{
    private $promotionService;

    /**
     * ArrayAggregatorService constructor.
     * @param $promotionService
     */
    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    /**
     * @param $products Product[]
     * @param $productsToDisplay
     *
     */
    public function aggregateProductsToDisplay($products, &$productsToDisplay)
    {
        foreach ($products as $product) {
            foreach ($product->getStocks() as $stock) {
                if ($stock->getQuantity() > 0 && $stock->isIsActive()) {
                    $maxPromotion = $this->getPromotionService()->findMaxPromotionForProduct($product);
                    $productsToDisplay[] = ["product" => $product, "notEmptyId" => $stock->getId(), "maxPromotion" => $maxPromotion];
                    $maxPromotion = null;
                    continue 2;
                }
            }
        }
    }

    /**
     * @param $product Product
     * @param $stocksToShow
     * @param $finalPrice
     * @param $detailedStock Stock
     * @param $currStockActivePromotion
     */
    public function aggregateStocksToDisplayByProduct($product, &$stocksToShow,&$finalPrice,$detailedStock,&$currStockActivePromotion)
    {
        foreach ($product->getStocks() as $stock) {
            if ($stock->getQuantity() > 0 && $stock->isIsActive()) {
                $activePromotion = $this->getPromotionService()->findMaxPromotionForStock($stock);
                if ($activePromotion) {
                    if ($stock->getId() === $detailedStock->getId()) {
                        $finalPrice = $finalPrice - ($finalPrice * ($activePromotion->getPercentage() / 100));
                        $currStockActivePromotion = $activePromotion;

                    }
                }
                $stocksToShow[] = ["stock" => $stock, "activePromotion" => $activePromotion];
                $activePromotion = null;
            }
        }
        usort($stocksToShow, function ($a, $b) {
            return $a["stock"]->getSize()->getName()<=>$b["stock"]->getSize()->getName();
        });

    }

    /**
     * @param $em ObjectManager
     * @param $detailedStock
     * @param $product
     * @param $productPromotionsToDisplay
     * @param $promotionsToDisplay
     */
    public function aggregatePromotionsToBeShownForPromoting($em, $detailedStock, $product, &$productPromotionsToDisplay, &$promotionsToDisplay)
    {
        $promotionService = $this->getPromotionService();
        $notExpired = $em->getRepository(Promotion::class)->findAllNotExpiredDESC();
        $biggestNotExpired =$promotionService->findBiggestNotExpiredForStock($detailedStock);
        $productMax = $promotionService->findMaxPromotionForProduct($product);
        $now = new \DateTime();
        foreach ($notExpired as $promotion) {
            if ($productMax === null || $promotion->getPercentage() >= $productMax->getPercentage() && $promotion->getEndsOn() >= $now) {
                $productPromotionsToDisplay[] = $promotion;
            }

            if ($biggestNotExpired === null || $promotion->getPercentage() > $biggestNotExpired->getPercentage()) {
                $promotionsToDisplay[] = $promotion;
            }
        }
    }
    public function sortBy($sortBy,&$productsToDisplay ,$direction)
    {
        uasort($productsToDisplay,function ($a,$b) use($sortBy,$direction)
        {
            if(strtolower($direction)==="asc")
            {
                return call_user_func([$a["product"],"get".ucfirst($sortBy)]) > call_user_func([$b["product"],"get".ucfirst($sortBy)]);
            }else{

                return call_user_func([$a["product"],"get".ucfirst($sortBy)]) < call_user_func([$b["product"],"get".ucfirst($sortBy)]);
            }
        });

    }


    /**
     * @return PromotionService
     */
    public function getPromotionService()
    {
        return $this->promotionService;
    }

    /**
     * @param mixed $promotionService
     */
    public function setPromotionService($promotionService)
    {
        $this->promotionService = $promotionService;
    }

}