<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Stock;
use AppBundle\Form\StockType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends Controller
{

    /**
     * @Route("/admin/createProduct", name="createProduct")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createProduct(Request $request)
    {
        // in order to create product we have to create the initial stock
        // Everything needed to create a stock is chained as embedded forms in the StockType
        $stock = new Stock();

        $form = $this->createForm(StockType::class, $stock);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("image")->getData();
            $product_image = $this->get("app.image_uploader")->upload($image);
            $stock->setIsActive(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($stock->getProduct());
            $product_image->setProduct($stock->getProduct());
            $em->persist($product_image);
            $em->persist($stock);
            $em->flush();
            return $this->redirectToRoute("adminPanel");
        }
        return $this->render("@App/admin/createProductView.html.twig", ["form" => $form->createView(),]);
    }



    /**
     * @Route("/admin/create/{class}",name="createSimpleObject")
     * @param Request $request
     * @param $class
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createSimpleEntity(Request $request, $class)
    {
        $class = ucfirst(strtolower($class));
        $namspace = "AppBundle\\Entity\\";
        $fullName =$namspace.$class;
        $object = new $fullName();
        $form = $this->createForm("AppBundle\\Form\\".$class."Type", $object);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->addFlash("success",$class . " successfully created");
            return $this->redirectToRoute("homepage");
        }
        return $this->render("@App/admin/create".$class.".html.twig", ["form" => $form->createView()]);
    }

}
