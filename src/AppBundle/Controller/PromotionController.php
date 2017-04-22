<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Stock;
use AppBundle\Models\PromotionsViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class PromotionController extends Controller
{
    /**
     * @Route("/promotions",name="promotions")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository(Category::class)->findAll();
        $productsToShow = null;
        /** @var Promotion[] $promotions */
        $promotions = $em->getRepository(Promotion::class)->findAllActiveDESC();

        foreach ($promotions as $promotion) {
            /** @var Stock[]|ArrayCollection $stocks */
            $stocks = $promotion->getStocks();
            if (count($stocks) > 0) {
                foreach ($stocks as $stock) {
                    if ($stock->getQuantity() > 0) {
                        $productOfStock = $stock->getProduct();

                        if ($productsToShow === null || (!array_key_exists($productOfStock->getId(), $productsToShow))) {
                            $productsToShow[$productOfStock->getId()] = ["product" => $productOfStock, "promotion" => $promotion, "notEmptyId" => $stock->getId()];


                        }
                    }
                }
            }
        }
        $model = new PromotionsViewModel($categories, $productsToShow);


        return $this->render('@App/Listing Products/promotionsView.html.twig', ["model" => $model]);
    }
}
