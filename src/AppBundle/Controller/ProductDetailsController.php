<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Stock;
use AppBundle\Models\DetailsViewModel;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\SizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

        /** @var Stock $stock */
        $detailedStock = $em->getRepository(Stock::class)->findOneBy(["id" => $id]);
        if ($detailedStock == null || $detailedStock->getQuantity() == 0) {
            return $this->redirectToRoute("homepage");
        }
        /** @var Product $product */
        $product = $detailedStock->getProduct();
        $stocksToShow = [];
        $activePromotion = null;
        $finalPrice = $detailedStock->getProduct()->getPrice();
        $currStockActivePromotion = null;

        foreach ($product->getStocks() as $stock) {
            if ($stock->getQuantity() > 0) {
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
        $model = new DetailsViewModel($categories, $detailedStock, $product, $stocksToShow, $finalPrice, $currStockActivePromotion);
        return $this->render("@App/Listing Products/detailsView.html.twig", array("model" => $model));
    }
}
