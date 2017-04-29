<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Status;
use AppBundle\Entity\Stock;
use AppBundle\Entity\User;
use AppBundle\Models\OrdersViewModel;
use AppBundle\Repository\StockRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;


class OrderController extends Controller
{

    const ADDED_STATUS = 1;
    const DEFAULT_QUANTITY = 1;
    const DEFAULT_PAGE_LIMIT = 10;
    const DEFAULT_SENT_STATUS = 4;
    const DEFAULT_REFUSED_STATUS = 5;
    const DEFAULT_REQUESTED_STATUS = 2;
    const DEFAULT_CONFIRMED_STATUS = 6;


    /**
     * @Route("/order/{id}", name="orderProduct")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function orderProduct($id)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        /** @var StockRepository $stockRepo */
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository(Status::class)->findOneBy(["id" => self::ADDED_STATUS]);
        $stock = $em->getRepository(Stock::class)->findOneBy(['id' => $id]);
        $userOrderOfCurrStock = null;
        foreach ($currentUser->getOrders() as $order) {
            if ($stock === $order->getStock() && $order->getStatus()->getId() === self::ADDED_STATUS) {
                $userOrderOfCurrStock = $order;
            }
        }
        if (!$userOrderOfCurrStock) {
            $order = new ProductOrder();
            $order->setUser($currentUser);
            $order->setStock($stock);
            $order->setStatus($status);
            $order->setQuantity(self::DEFAULT_QUANTITY);
            $calcPrice = $order->getStock()->getProduct()->getPrice();
            $maxPromotion = $this->get("app.promotion")->findMaxPromotionForStock($stock);
            if ($maxPromotion) {
                $calcPrice = $calcPrice - ($calcPrice * ($maxPromotion->getPercentage() / 100));
            }
            $order->setCalculatedSinglePrice($calcPrice);
            $order->setFinalPrice($order->getQuantity() * $order->getCalculatedSinglePrice());
            $order->setAddedOn(new \DateTime());
            $em->persist($order);
            $this->addFlash("success", "You have successfully ordered " . self::DEFAULT_QUANTITY . " " . $order->getStock()->getProduct()->getName());
        } else {
            $now = new \DateTime();
            /** @var ProductOrder $order */
            $order = $userOrderOfCurrStock;
            /** @var Promotion $maxPromotion */
            $maxPromotion = $this->get("app.promotion")->findEffectivePromotionForOrder($order);
            if ($maxPromotion->getStartsOn() <= $now && $maxPromotion->getEndsOn() >= $now) {
                $order->setQuantity($order->getQuantity() + self::DEFAULT_QUANTITY);
                $calcPrice = $order->getStock()->getProduct()->getPrice();
                if ($maxPromotion) {
                    $calcPrice = $calcPrice - ($calcPrice * ($maxPromotion->getPercentage() / 100));
                }
                $order->setCalculatedSinglePrice($calcPrice);
                $order->setFinalPrice($order->getQuantity() * $order->getCalculatedSinglePrice());
                $this->addFlash("success", "You have successfully added " . self::DEFAULT_QUANTITY . " " . $order->getStock()->getProduct()->getName());
            } else {
                $this->addFlash("danger", "Cannot complete your order of " . $order->getStock()->getProduct()->getName() . " because there are differences between prices. Please delete or confirm your order in the cart to be able to add this stock");
            }

        }
        $em->flush();
        return $this->redirectToRoute("detailsView", ["id" => $id]);
    }

    /**
     * @Route("/delete/order/{id}", name="deleteOrder")
     *
     * */
    public function deleteOrder($id)
    {

        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(ProductOrder::class)->findOneBy(["id" => $id]);
        $em->remove($order);
        $em->flush();
        return $this->redirectToRoute("checkout");
    }

    /**
     * @Route("/edit/order/{id}",name="editOrder")
     */
    public function editOrder(Request $request, $id)
    {
        $form = $this->createFormBuilder(null, ["csrf_protection" => false])->add('qty', NumberType::class)->getForm();
        if ($request->isMethod('POST')) {
            $form->submit(["qty" => $request->request->get("qty")]);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                /** @var ProductOrder $order */
                $order = $em->getRepository(ProductOrder::class)->findOneBy(['id' => $id]);
                $order->setQuantity($data["qty"]);
                $order->setFinalPrice($order->getQuantity() * $order->getCalculatedSinglePrice());
                $em->persist($order);
                $em->flush();
            }
        }
        return $this->redirectToRoute("checkout");
    }

    /**
     * @Route("/admin/view/orders/{status}",name="viewOrders")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listOrders(Request $request, $status)
    {
        $status = strtolower($status);
        $em = $this->getDoctrine()->getManager();
        /** @var Status $status */
        $status = $em->getRepository(Status::class)->findOneBy(["name" => $status]);

        $categories = $em->getRepository(Category::class)->findAll();
        $orders = $em->getRepository(ProductOrder::class)->getPaginationQueryByStatus($status->getId());
        $page = $request->get("page");
        if (!$page) {
            $page = 1;
        }
        $paginator = $this->get("knp_paginator");
        $orders = $paginator->paginate($orders, $page, self::DEFAULT_PAGE_LIMIT);
        $direction = strtolower($request->get("direction"));

        $model = new OrdersViewModel($categories, $orders, $status->getName());
        return $this->render("@App/admin/" . $status->getName() . "View.html.twig", ["model" => $model, "direction" => $direction]);
    }

    /** @Route("/admin/send/{order_id}",name="sendOrder")
     * @param $order_id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendOrder($order_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(ProductOrder::class)->findOneBy(["id" => $order_id]);
        $status = $em->getRepository(Status::class)->findOneBy(["id" => self::DEFAULT_SENT_STATUS]);
        if (!($order->getStatus()->getId() >= self::DEFAULT_REQUESTED_STATUS && $order->getStatus()->getId() <= self::DEFAULT_REFUSED_STATUS)) {
            $this->addFlash("error", "You cannot send that order - it has status " . $order->getStatus()->getName());
            return $this->redirectToRoute("viewOrders", ["status" => $order->getStatus()->getName()]);
        }
        $order->setStatus($status);
        $em->persist($order);
        $em->flush();
        $this->addFlash("success", "successfully sent");
        return $this->redirect($request->server->get("HTTP_REFERER"));
    }

    /** @Route("/admin/refuse/{order_id}",name="refuseOrder")
     * @param $order_id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseOrder($order_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var ProductOrder $order */
        $order = $em->getRepository(ProductOrder::class)->findOneBy(["id" => $order_id]);
        if (!$order->getStatus()->getId() >= self::DEFAULT_REQUESTED_STATUS && $order->getStatus()->getId() <= self::DEFAULT_SENT_STATUS) {
            $this->addFlash("error", "You cannot refuse that order - it has status " . $order->getStatus()->getName());
            return $this->redirectToRoute("viewOrders", ["status" => $order->getStatus()->getName()]);
        }
        $status = $em->getRepository(Status::class)->findOneBy(["id" => self::DEFAULT_REFUSED_STATUS]);
        $stock = $order->getStock();
        //return the quantity that has been removed
        $stock->setQuantity($stock->getQuantity() + $order->getQuantity());
        $order->setStatus($status);
        $em->persist($stock);
        $em->persist($order);
        $em->flush();
        $this->addFlash("success", "successfully refused");
        return $this->redirect($request->server->get("HTTP_REFERER"));
    }

    /** @Route("/admin/confirm/{order_id}",name="confirm_Order")
     * @param $order_id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmOrder($order_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var ProductOrder $order */
        $order = $em->getRepository(ProductOrder::class)->findOneBy(["id" => $order_id]);
        if ($order->getStatus()->getId() != self::DEFAULT_SENT_STATUS) {
            $this->addFlash("error", "You cannot refuse that order - it has status " . $order->getStatus()->getName());
            return $this->redirectToRoute("viewOrders", ["status" => $order->getStatus()->getName()]);
        }
        $status = $em->getRepository(Status::class)->findOneBy(["id" => self::DEFAULT_CONFIRMED_STATUS]);
        $stock = $order->getStock();
        //return the quantity that has been removed
        $stock->setQuantity($stock->getQuantity() + $order->getQuantity());
        $order->setStatus($status);
        $em->persist($stock);
        $em->persist($order);
        $em->flush();
        $this->addFlash("success", "successfully confirmed");
        return $this->redirect($request->server->get("HTTP_REFERER"));
    }
}
