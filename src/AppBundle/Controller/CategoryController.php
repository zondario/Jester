<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;

use AppBundle\Models\CategoryViewModel;
use AppBundle\Models\HomeViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends Controller
{
    const DEFAULT_PRODUCT_PER_PAGE = 6;

    /**
     * @Route("category/{name}", name="categoryView")
     * @param $name
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($name, Request $request)
    {
        /** @var Category[] $categories */
        $categories = $this->getCategories();
        /** @var Category $activeCategory */
        $activeCategory = null;
        $maxPromotion = null;
        $page = $request->get("page");
        if (!$page) {
            $page = 1;
        }
        foreach ($categories as $category) {
            if (strtolower($category->getName()) == strtolower($name)) {
                $activeCategory = $category;
            }
        }
        $productsToDisplay = [];
        $this->get("app.aggregator")->aggregateProductsToDisplay($activeCategory->getProducts(), $productsToDisplay);
        if ($request->get("sortBy") && $request->get("direction")) {
            $this->get("app.aggregator")->sortBy($request->get("sortBy"), $productsToDisplay, $request->get("direction"));
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
        /** @var Category[] $categories */
        $categories = $this->getCategories();
        $model = new HomeViewModel($categories);
        return $this->render("@App/Home/homePage.html.twig", ["model" => $model]);
    }


    private function getCategories()
    {
        return $this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();
    }

}
