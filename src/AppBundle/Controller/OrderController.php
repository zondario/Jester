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

class OrderController extends Controller
{
     const DEFAULT_STATUS = "added";
    /**
     * @Route("/order/{id}/{quantity}", name="orderProduct")
     */
    public function orderProduct($id,$quantity)
    {
        /** @var User $currentUser */
        $currentUser= $this->getUser();
        /** @var StockRepository $stockRepo */
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository(Status::class)->findOneBy(["name"=>self::DEFAULT_STATUS]);
       $stock = $em->getRepository(Stock::class)->findOneBy(['id'=>$id]);
       $order = new ProductOrder();
       $order->setUser($currentUser);
       $order->setStock($stock);
       $order->setStatus($status);
       $order->setQuantity($quantity);
       $calcPrice = $order->getStock()->getProduct()->getPrice();
       if($stock->getPromotions()->count()>0){
           /** @var Promotion[]|ArrayCollection $promotions */
           $promotions = $stock->getPromotions()->toArray();
           sort($promotions);
           $maxPromotion = $promotions[0];
           $now = new \DateTime("now");
           if($maxPromotion->getStartsOn()<=$now && $maxPromotion->getEndsOn()>=$now){
               $calcPrice = $calcPrice - ($calcPrice * ($maxPromotion->getPercentage()/100));
           }


       }
       $order->setCalculatedSinglePrice($calcPrice);
       $order->setFinalPrice($order->getQuantity()*$order->getCalculatedSinglePrice());
       $em->persist($order);
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
