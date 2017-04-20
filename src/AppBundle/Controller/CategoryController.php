<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;

use AppBundle\Models\CategoryViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CategoryController extends Controller
{
    /**
     * @Route("category/{name}", name="categoryView")
     *
     */
    public function categoryAction($name)
    {
        /** @var Category[] $categories */
        $categories=$this->getCategories();
        /** @var Category $activeCategory */
        $activeCategory = null;
        $maxPromotion = null;
        foreach ($categories as $category) {
            if(strtolower($category->getName())==strtolower($name)){
                $activeCategory=$category;
            }
        }
        $productsToDisplay = [];
        foreach ($activeCategory->getProducts() as $product){
            foreach ($product->getStocks() as $stock){
                if($stock->getQuantity()>0){
                    $maxPromotion = $this->get("app.promotion")->findMaxPromotionForProduct($product);
                    $productsToDisplay[]=["product"=>$product,"notEmptyId"=>$stock->getId(),"maxPromotion"=>$maxPromotion];
                    $maxPromotion=null;
                    continue 2;
                }
            }
        }
        $model = new CategoryViewModel($activeCategory,$categories,$productsToDisplay);
        return $this->render("@App/Listing Products/categoryView.html.twig",array("model"=>$model));
    }
    /**
     *@Route("/", name="homepage")
     */
    public function homeAction(){
        return $this->categoryAction("Bikes");
    }


    private function getCategories()
    {
        return $this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();
    }

}
