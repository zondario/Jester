<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Status;
use AppBundle\Entity\User;
use AppBundle\Models\CheckoutViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class CartController extends Controller
{
    const STATUS_REQUESTED_ID= 2;
    const STATUS_ADDED_ID = 1;
    /**
     *@Route("/checkout", name="checkout")
     */
    public function checkoutView()
    {
        /** @var User $currentUser */
        $currentUser=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $categories =$em->getRepository(Category::class)->findAll();
        $ordersToShow = [];
        $orders = $currentUser->getOrders();
        foreach ($orders as $order)
        {
            if($order->getStatus()->getId()==self::STATUS_ADDED_ID){
                $ordersToShow[]=$order;
            }
        }
        $model = new CheckoutViewModel($categories,$ordersToShow);
        return $this->render('@App/Cart/cartCheckoutView.html.twig',["model"=>$model]);
    }
    /**
     *@Route("/checkout/submit",name="checkoutSubmit")
     */
    public function checkout()
    {
        $now = new \DateTime();
        $em =  $this->getDoctrine()->getManager();

        /** @var User $currentUser */
        $currentUser=$this->getUser();

        foreach ($currentUser->getOrders() as $order) {
            /** @var Promotion[]|ArrayCollection $promotions */
            $promotions =$order->getStock()->getPromotions()->toArray();
            $effectivePromotion = null;
            if(count($promotions)>0){
                usort(
                    $promotions,function ($a, $b){
                    return $b->compareTo($a);
                });
                foreach ($promotions as $promotion) {
                    $then = $order->getAddedOn();
                    if($promotion->getStartsOn()<=$then && $promotion->getEndsOn()>=$then){
                        $effectivePromotion = $promotion;
                        break;

                    }
                }

                if(!($effectivePromotion->getStartsOn()<=$now && $effectivePromotion->getEndsOn()>=$now)){
                    $this->addFlash("danger","Your order of ".$order->getStock()->getProduct()->getName()." has been ordered within a promotion that is no longer valid, please delete it and order again");
                    continue;
                }
            }
            $status =$em->getRepository(Status::class)->findOneBy(["id"=> max($order->getStatus()->getId(),self::STATUS_REQUESTED_ID)]);
            $order->setStatus($status);
            $order->setOrderedOn($now);
            $em->persist($order);
        }
        $em->flush();
       return $this->redirectToRoute("checkout");
    }
}
