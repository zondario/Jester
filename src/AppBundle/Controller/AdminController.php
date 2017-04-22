<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Color;
use AppBundle\Entity\Image;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Size;
use AppBundle\Entity\Stock;
use AppBundle\Form\CategoryType;
use AppBundle\Form\ColorType;
use AppBundle\Form\ImageType;
use AppBundle\Form\SizeType;
use AppBundle\Form\StockType;
use AppBundle\Models\AdminPanelViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    const IMAGES_VIEW_DIRECTORY = "../../images/productImages/";

    /**
     * @Route("/admin",name="adminPanel")
     */
    public function adminPanel()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $model = new AdminPanelViewModel($categories);
        return $this->render('@App/admin/adminPanel.html.twig', array('model' => $model));
    }

    /**
     * @Route("/admin/create/product", name="createProduct")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createProduct(Request $request)
    {
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
     * @Route("/admin/create/category",name="createCategory")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute("homepage");
        }
        return $this->render("@App/admin/createCategory.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/admin/create/size",name="createSize")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createSize(Request $request)
    {
        $category = new Size();
        $form = $this->createForm(SizeType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash("success", $category->getName() . " successfully created");
            return $this->redirectToRoute("adminPanel");
        }
        return $this->render("@App/admin/createSize.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/admin/create/color",name="createColor")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createColor(Request $request)
    {
        $category = new Color();
        $form = $this->createForm(ColorType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash("success", $category->getName() . " successfully created");
            return $this->redirectToRoute("adminPanel");
        }
        return $this->render("@App/admin/createColor.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/admin/promote/stock/{id}", name="promoteStock", methods={"POST"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function promoteStock(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository(Stock::class)->findOneBy(["id"=>$id]);
        $promotion = $em->getRepository(Promotion::class)->findOneBy(["id"=>$request->get("promotion")]);
        if($stock!=null && $promotion!= null) {
            $stock->getPromotions()->add($promotion);
            $promotion->getStocks()->add($stock);
            $em->persist($stock);
            $em->persist($promotion);
            $em->flush();
        }
        return $this->redirectToRoute("detailsView",["id"=>$id]);

    }
}
