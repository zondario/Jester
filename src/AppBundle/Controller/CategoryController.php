<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/category/{name}", name="homepage")
     *
     */
   public function categoryAction($name)
   {
       $em =$this->getDoctrine()->getManager();
       $categories=$em->getRepository(Category::class)->findAll();
       return $this->render("@App/Listing Products/categoryView.html.twig",array("categories"=>$categories));
   }
}
