<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Stock;
use AppBundle\Models\DetailsViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProductDetailsController extends Controller
{
    /**
     * @Route("/details/{id}", name="detailsView")
     */
    public function detailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();
        $promotionsToDisplay = [];
        $productPromotionsToDisplay = [];
        /** @var Stock $stock */
        $detailedStock = $em->getRepository(Stock::class)->findOneBy(["id" => $id]);
        if ($detailedStock === null) {
            $this->addFlash("error", "The stock you requested was not found sorry :(");
            return $this->redirectToRoute("homepage");
        }
        if (!$detailedStock->isIsActive()) {
            $this->addFlash("danger", "This product is not active");
            return $this->redirectToRoute("homepage");
        }

        if ($detailedStock->getQuantity() == 0) {
            $this->addFlash("danger", "Sorry we are out of stock");
            return $this->redirectToRoute("homepage");
        }
        /** @var Product $product */
        $product = $detailedStock->getProduct();
        $stocksToShow = [];
        $activePromotion = null;
        $finalPrice = $detailedStock->getProduct()->getPrice();
        $currStockActivePromotion = null;

        foreach ($product->getStocks() as $stock) {
            if ($stock->getQuantity() > 0 && $stock->isIsActive()) {
                $activePromotion = $this->get("app.promotion")->findMaxPromotionForStock($stock);
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
        if ($this->isGranted("ROLE_ADMIN")) {
            $notExpired = $em->getRepository(Promotion::class)->findAllNotExpiredDESC();
            $biggestNotExpired = $this->get("app.promotion")->findBiggestNotExpiredForStock($detailedStock);
            $productMax = $this->get("app.promotion")->findMaxPromotionForProduct($product);
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

        usort($stocksToShow, function ($a, $b) {
            return $a["stock"]->getSize()->getName()<=>$b["stock"]->getSize()->getName();
        });
        $model = new DetailsViewModel($categories,
            $detailedStock,
            $product,
            $stocksToShow,
            $finalPrice,
            $currStockActivePromotion,
            $promotionsToDisplay,
            $productPromotionsToDisplay);

        return $this->render("@App/Listing Products/detailsView.html.twig", array("model" => $model));
    }
}
