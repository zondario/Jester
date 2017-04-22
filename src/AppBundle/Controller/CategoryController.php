<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;

use AppBundle\Models\CategoryViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends Controller
{
    const DEFAULT_PRODUCT_PER_PAGE = 6;

    /**
     * @Route("category/{name}", name="categoryView")
     *
     */
    public function categoryAction($name, Request $request)
    {
        /** @var Category[] $categories */
        $categories = $this->getCategories();
        /** @var Category $activeCategory */
        $activeCategory = null;
        $maxPromotion = null;
        foreach ($categories as $category) {
            if (strtolower($category->getName()) == strtolower($name)) {
                $activeCategory = $category;
            }
        }
        $productsToDisplay = [];
        foreach ($activeCategory->getProducts() as $product) {
            foreach ($product->getStocks() as $stock) {
                if ($stock->getQuantity() > 0) {
                    $maxPromotion = $this->get("app.promotion")->findMaxPromotionForProduct($product);
                    $productsToDisplay[] = ["product" => $product, "notEmptyId" => $stock->getId(), "maxPromotion" => $maxPromotion];
                    $maxPromotion = null;
                    continue 2;
                }
            }
        }
        $page = $request->get("page");
        if (!$page) {
            $page = 1;
        }
        $paginator = $this->get("knp_paginator");
        $productsToDisplay = $paginator->paginate($productsToDisplay, $page, self::DEFAULT_PRODUCT_PER_PAGE);
        $model = new CategoryViewModel($activeCategory, $categories, $productsToDisplay);
        return $this->render("@App/Listing Products/categoryView.html.twig", array("model" => $model));
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homeAction()
    {
        return $this->categoryAction("Bikes",new Request());
    }


    private function getCategories()
    {
        return $this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();
    }

}
