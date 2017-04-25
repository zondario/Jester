<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Category;

use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Promotion;


use AppBundle\Entity\Status;
use AppBundle\Entity\Stock;
use AppBundle\Form\ImageType;
use AppBundle\Models\AdminPanelViewModel;
use AppBundle\Models\OrdersViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    const IMAGES_VIEW_DIRECTORY = "../../images/productImages/";
    const DEFAULT_REQUESTED_ID = 2;
    const DEFAULT_SENT_ID = 4;
    const DEFAULT_PAGE_LIMIT = 10;

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
     * @Route("/admin/promote/stock/{id}", name="promoteStock", methods={"POST"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function promoteStock(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository(Stock::class)->findOneBy(["id" => $id]);
        $promotion = $em->getRepository(Promotion::class)->findOneBy(["id" => $request->get("promotion")]);
        if ($stock != null && $promotion != null) {
            $stock->getPromotions()->add($promotion);
            $promotion->getStocks()->add($stock);
            $em->persist($stock);
            $em->persist($promotion);
            $em->flush();
        }
        return $this->redirectToRoute("detailsView", ["id" => $id]);

    }

    /**
     * @Route("/admin/promote/product/{id}", methods={"POST"},name="promoteProduct")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function promoteProduct($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);
        /** @var Stock[] $stocks */
        $stocks = $product->getStocks();
        $promotion = $em->getRepository(Promotion::class)
            ->findOneBy(["id" => $request->get("productPromotion")]);
        if ($product != null && $promotion != null) {
            foreach ($stocks as $stock) {
                if (!in_array($promotion, $stock->getPromotions()->toArray())) {
                    $stock->getPromotions()->add($promotion);
                    $promotion->getStocks()->add($stock);
                    $em->persist($stock);
                    $em->persist($product);
                }
            }
            $em->flush();
            $this->addFlash("success", $product->getName() . " successfully promoted");
        }
        return $this->redirect($request->server->get("HTTP_REFERER"));
    }

    /**
     * @Route("/admin/add/image/{productId}",name="addImage")
     * @param $productId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addImage($productId, Request $request)
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image, ["csrf_protection" => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository(Product::class)->findOneBy(["id" => $productId]);
            $image = $this->get("app.image_uploader")->upload($image);
            $image->setProduct($product);
            $em->persist($image);
            $em->flush();
            $this->addFlash("success", "Image added");
        } elseif ($form->getErrors(true)->count() > 0) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash("danger", $error->getMessage());
            }
        }

        return $this->redirect($request->server->get("HTTP_REFERER"));
    }

    /**
     * @Route("/admin/view/orders/{status}",name="viewOrders")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listOrders(Request $request,$status){
        $status = strtolower($status);
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository(Status::class)->findOneBy(["name"=>$status]);
        $categories = $em->getRepository(Category::class)->findAll();
        $orders = $em->getRepository(ProductOrder::class)->getPaginationQueryByStatus($status->getId());
        $page = $request->get("page");
        if (!$page) {
            $page = 1;
        }
        $paginator = $this->get("knp_paginator");
        $orders=$paginator->paginate($orders,$page, self::DEFAULT_PAGE_LIMIT);

        $model = new OrdersViewModel($categories,$orders);
        return $this->render("@App/admin/".$status->getName()."View.html.twig",["model"=>$model]);
    }
}
