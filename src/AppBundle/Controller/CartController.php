<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Status;
use AppBundle\Entity\User;
use AppBundle\Models\CheckoutViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller
{
    const STATUS_REQUESTED_ID = 2;
    const STATUS_ADDED_ID = 1;

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkoutView()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();
        $ordersToShow = [];
        $orders = $currentUser->getOrders();

        foreach ($orders as $order) {
            if ($order->getStatus()->getId() == self::STATUS_ADDED_ID) {
                $ordersToShow[] = $order;
            }
        }
        $model = new CheckoutViewModel($categories, $ordersToShow);
        return $this->render('@App/Cart/cartCheckoutView.html.twig', ["model" => $model]);
    }

    /**
     * @Route("/checkout/submit",name="checkoutSubmit")
     */
    public function checkout()
    {
        $now = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        /** @var ProductOrder[] $ordersForUser */
        $ordersForUser = $this->getDoctrine()
            ->getRepository(ProductOrder::class)
            ->getOrdersForUserWithStatus($currentUser, self::STATUS_ADDED_ID);

        if (count($ordersForUser) > 0) {
            foreach ($ordersForUser as $order) {
                if ($order->getStock()->isisActive() && $order->getStock()->getQuantity() > 0) {
                    $stock = $order->getStock();
                    if ($stock->getQuantity() >= $order->getQuantity()) {
                        $effectivePromotion = $this->get("app.promotion")->findEffectivePromotionForOrder($order);
                        if ($effectivePromotion) {
                            if (!($effectivePromotion->getStartsOn() <= $now && $effectivePromotion->getEndsOn() >= $now)) {
                                $this->addFlash("danger", "Your order of " . $order->getStock()->getProduct()->getName() . " has been ordered within a promotion that is no longer valid, please delete it and order again");
                                continue;
                            }
                        }
                        $stock->setQuantity($stock->getQuantity() - $order->getQuantity());
                        $status = $em->getRepository(Status::class)->findOneBy(["id" => max($order->getStatus()->getId(), self::STATUS_REQUESTED_ID)]);
                        $order->setStatus($status);
                        $order->setOrderedOn($now);
                        $em->persist($order);
                        $em->persist($stock);
                    } else {
                        $this->addFlash("danger", "Sorry the quantity you requested for " . $order->getStock()->getProduct()->getName() . " is not available");
                    }
                } else {
                    $em->remove($order);
                    $this->addFlash("danger", "Sorry but the product " . $order->getStock()->getProduct()->getName() . " is not available!");
                }
            }
            $em->flush();
        }
        return $this->redirectToRoute("checkout");

    }
}
