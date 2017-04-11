<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;

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
        $activeCategory = null;
        foreach ($categories as $category) {
            if($category->getName()==$name){
                $activeCategory=$category;
            }
        }
        return $this->render("@App/Listing Products/categoryView.html.twig",array("categories"=>$categories,"activeCategory"=>$activeCategory));
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
