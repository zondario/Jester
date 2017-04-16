<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Status;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CartController extends Controller
{
    const STATUS_PLACED_ORDER_ID= 2;
    /**
     *@Route("/checkout", name="checkout")
     */
    public function checkoutView()
    {
        /** @var User $currentUser */
        $currentUser=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $categories =$em->getRepository(Category::class)->findAll();
        $orders = $currentUser->getOrders();
        return $this->render('@App/Cart/cartCheckoutView.html.twig',['categories'=>$categories,"orders"=>$orders]);
    }
    /**
     *@Route("/checkout/submit",name="checkoutSubmit")
     */
    public function checkout()
    {
        $em =  $this->getDoctrine()->getManager();

        /** @var User $currentUser */
        $currentUser=$this->getUser();

        foreach ($currentUser->getOrders() as $order) {
            $status =$em->getRepository(Status::class)->findOneBy(["id"=> max($order->getStatus()->getId(),self::STATUS_PLACED_ORDER_ID)]);
            $order->setStatus($status);
            $order->setOrderedOn(new \DateTime());

            $em->persist($order);
        }
        $em->flush();
       return $this->redirectToRoute("homepage");
    }
}
