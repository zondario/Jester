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
use AppBundle\Entity\Size;
use AppBundle\Entity\Stock;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use function Sodium\compare;


class ArrayAggregatorService
{
    private $promotionService;

    /**
     * ArrayAggregatorService constructor.
     * @param PromotionService $promotionService
     * @param EntityManager $entityManager
     */
    public function __construct(PromotionService $promotionService, EntityManager $entityManager)
    {
        $this->promotionService = $promotionService;
        /** @var EntityManager $entityManager */
        $this->em = $entityManager;
    }

    /**
     * @param $products Product[]
     * @param $productsToDisplay
     *
     */
    public function aggregateProductsToDisplay($categoryId, &$productsToDisplay )
    {
        $productRepo =$this->em->getRepository(Product::class);
        $active = $productRepo->getAllActiveProducts($categoryId);
        $productsToDisplay = $active;
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
                        $finalPrice = $finalPrice - ($finalPrice * ($activePromotion / 100));
                        $currStockActivePromotion = $activePromotion;

                    }
                }
                $stocksToShow[] = ["stock" => $stock, "activePromotion" => $activePromotion];
                $activePromotion = null;
            }
        }

        usort($stocksToShow, function ($a, $b) {

            return strcmp($a["stock"]->getSize()->getName(),$b["stock"]->getSize()->getName());
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
        $biggestNotExpired = $promotionService->findBiggestNotExpiredForStock($detailedStock);
        $productMax = $promotionService->findMaxPromotionForProduct($product);
        $now = new \DateTime();
        foreach ($notExpired as $promotion) {

            if ($productMax === null || $promotion->getPercentage() >= $productMax) {

                $productPromotionsToDisplay[] = $promotion;
            }

            if ($biggestNotExpired === null || $promotion->getPercentage() > $biggestNotExpired) {
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
                return call_user_func([$a,"get".ucfirst($sortBy)]) > call_user_func([$b,"get".ucfirst($sortBy)]);
            }else{

                return call_user_func([$a,"get".ucfirst($sortBy)]) < call_user_func([$b,"get".ucfirst($sortBy)]);
            }
        });

    }

    /**
     * @param $promotions Promotion[]
     * @param $productsToShow
     *
     */
    public function aggregateByPromotions($promotions, &$productsToShow)
    {
        foreach ($promotions as $promotion) {
            /** @var Stock[]|ArrayCollection $stocks */
            $stocks = $promotion->getStocks();
            if (count($stocks) > 0) {
                foreach ($stocks as $stock) {
                    if ($stock->getQuantity() > 0 && $stock->isIsActive()) {
                        $productOfStock = $stock->getProduct();
                        if ($productsToShow === null || (!array_key_exists($productOfStock->getId(), $productsToShow))) {
                            $productsToShow[$productOfStock->getId()] = ["product" => $productOfStock, "maxPromotion" => $promotion, "notEmptyId" => $stock->getId()];


                        }
                    }
                }
            }
        }
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