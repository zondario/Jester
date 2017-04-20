<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Status;
use AppBundle\Entity\Stock;
use AppBundle\Entity\User;
use AppBundle\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Repository\RepositoryFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

class OrderController extends Controller
{
     const ADDED_STATUS = 1;
     const DEFAULT_QUANTITY= 1;
    /**
     * @Route("/order/{id}", name="orderProduct")
     */
    public function orderProduct($id)
    {
       /** @var User $currentUser */
       $currentUser= $this->getUser();
       /** @var StockRepository $stockRepo */
       $em = $this->getDoctrine()->getManager();
       $status = $em->getRepository(Status::class)->findOneBy(["id"=>self::ADDED_STATUS]);
       $stock = $em->getRepository(Stock::class)->findOneBy(['id'=>$id]);
       $userOrderOfCurrStock = null;
        foreach ($currentUser->getOrders() as $order) {
            if($stock===$order->getStock()&&$order->getStatus()->getId()===self::ADDED_STATUS){
                $userOrderOfCurrStock = $order;
            }
       }
       if(!$userOrderOfCurrStock)
       {
           $order = new ProductOrder();
           $order->setUser($currentUser);
           $order->setStock($stock);
           $order->setStatus($status);
           $order->setQuantity(self::DEFAULT_QUANTITY);
           $calcPrice = $order->getStock()->getProduct()->getPrice();
           $maxPromotion = $this->get("app.promotion")->findMaxPromotionForStock($stock);
           if($maxPromotion){
               $calcPrice = $calcPrice - ($calcPrice * ($maxPromotion->getPercentage()/100));
           }
           $order->setCalculatedSinglePrice($calcPrice);
           $order->setFinalPrice($order->getQuantity()*$order->getCalculatedSinglePrice());
           $order->setAddedOn(new \DateTime());
           $em->persist($order);


       }else{
            $now = new \DateTime();
           $order = $userOrderOfCurrStock;
           $maxPromotion = $this->get("app.promotion")->findEffectivePromotionForOrder($order);
           if($maxPromotion->getStartsOn()<=$now && $maxPromotion->getEndsOn() >= $now) {
               $order->setQuantity($order->getQuantity() + self::DEFAULT_QUANTITY);
               $calcPrice = $order->getStock()->getProduct()->getPrice();


               if ($maxPromotion) {
                   $calcPrice = $calcPrice - ($calcPrice * ($maxPromotion->getPercentage() / 100));
               }
               $order->setCalculatedSinglePrice($calcPrice);
               $order->setFinalPrice($order->getQuantity() * $order->getCalculatedSinglePrice());
           }else
           {
               $this->addFlash("danger","Cannot complete your order of ".$order->getStock()->getProduct()->getName()." because there are differences between prices. Please delete or confirm your order in the cart to be able to add this stock");
           }

       }
        $em->flush();
       return $this->redirectToRoute("detailsView",["id"=>$id]);
    }
    /**
     * @Route("/delete/order/{id}", name="deleteOrder")
     *
     * */
    public function deleteOrder($id){

        $em = $this->getDoctrine()->getManager();
        $order=$em->getRepository(ProductOrder::class)->findOneBy(["id"=>$id]);
        $em->remove($order);
        $em->flush();
        return $this->redirectToRoute("checkout");
    }
    /**
     *@Route("/edit/order/{id}",name="editOrder")
     */
    public function editOrder(Request $request,$id){
       $form= $this->createFormBuilder(null,["csrf_protection"=>false])->add('qty',NumberType::class)->getForm();
        if ($request->isMethod('POST')) {
            $form->submit(["qty"=>$request->request->get("qty")]);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                /** @var ProductOrder $order */
                $order = $em->getRepository(ProductOrder::class)->findOneBy(['id'=>$id]);
                $order->setQuantity($data["qty"]);
                $order->setFinalPrice($order->getQuantity()*$order->getCalculatedSinglePrice());
                $em->persist($order);
                $em->flush();
            }
           return $this->redirectToRoute("checkout");

        }
    }
}
