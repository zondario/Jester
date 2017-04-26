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
        $this->get("app.aggregator")
            ->aggregateStocksToDisplayByProduct($product,
                $stocksToShow,
                $finalPrice,
                $detailedStock,
                $currStockActivePromotion);
        if ($this->isGranted("ROLE_ADMIN")) {
            $this->get("app.aggregator")
                ->aggregatePromotionsToBeShownForPromoting($em,
                    $detailedStock,
                    $product,
                    $productPromotionsToDisplay,
                    $promotionsToDisplay);
        }

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
