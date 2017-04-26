<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Models\SearchViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{
    /**
     * @Route("/search/",name="search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $terms
     */
    public function searchAction( Request $request)
    {
        $terms = $request->get("terms");
        $em = $this->getDoctrine()->getManager();
        $categories= $em->getRepository(Category::class)->findAll();
        $terms = "%".$terms."%";
        $products = $em->getRepository(Product::class)->getBySearchTerms($terms);
        $productsToShow=[];
        $this->get("app.aggregator")->aggregateProductsToDisplay($products,$productsToShow);
        $sortBy = $request->get("sortBy");
        $direction = $request->get("direction");
        if($sortBy && $direction)
        {
            $this->get("app.aggregator")->sortBy($request->get("sortBy"),$productsToShow,$request->get("direction"));
        }


        $model = new SearchViewModel($categories,$productsToShow,$terms);
       return $this->render("@App/Listing Products/search.html.twig",["model"=>$model]);
    }
}
