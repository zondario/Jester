<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends Controller
{
    /**
     * @Route("/admin/remove/stock/{id}",name="removeStock")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeStock($id)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository(Stock::class)->findOneBy(["id" => $id]);
        if (!is_null($stock)) {
            $stock->setIsActive(false);
            $em->persist($stock);
            $em->flush();
            $this->addFlash("success", "Stock with id " . $id . " successfully removed");
        }
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/admin/remove/product/{id}",name="removeProduct")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeProduct($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);
        if (!is_null($product)) {
            foreach ($product->getStocks() as $stock) {

                $stock->setIsActive(false);
                $em->persist($stock);
                $em->flush();
            }
            $this->addFlash("success", "Product with id " . $id . " successfully removed");
        }

        return $this->redirectToRoute("homepage");
    }
}
