<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Status;
use AppBundle\Entity\Stock;
use AppBundle\Entity\User;
use AppBundle\Repository\StockRepository;
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
       $order->setColor($stock->getColor()->getName());
       $order->setSize($stock->getSize()->getName());
       $order->setPrice($stock->getProduct()->getPrice());
       $order->setStatus($status);
       $order->setName($stock->getProduct()->getName());
       $order->setQuantity($quantity);
       $order->setFinalPrice($order->getQuantity()*$order->getPrice());
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
                $order->setFinalPrice($order->getQuantity()*$order->getPrice());
                $em->persist($order);
                $em->flush();
            }
           return $this->redirectToRoute("checkout");

        }
    }
}
