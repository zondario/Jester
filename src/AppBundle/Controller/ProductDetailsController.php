<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Stock;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\SizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProductDetailsController extends Controller
{
    /**
     *@Route("/details/{id}", name="detailsView")
     */
    public function detailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();

        $stock=$em->getRepository(Stock::class)->findOneBy(["id"=>$id]);
        /** @var Product $product */
        $product = $stock->getProduct();


        return $this->render("@App/Listing Products/detailsView.html.twig",array('categories'=>$categories,'product'=>$product,"stock"=>$stock));
    }
}
