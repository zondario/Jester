<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Stock;
use AppBundle\Models\PromotionsViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class PromotionController extends Controller
{
    /**
     * @Route("/promotions",name="promotions")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function promotionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository(Category::class)->findAll();
        $productsToShow = null;
        /** @var Promotion[] $promotions */
        $promotions = $em->getRepository(Promotion::class)->findAllActiveDESC();
        $this->get("app.aggregator")->aggregateByPromotions($promotions, $productsToShow);
        if ($request->get("sortBy") && $request->get("direction")) {
            $this->get("app.aggregator")->sortBy($request->get("sortBy"), $productsToShow, $request->get("direction"));
        }

        $model = new PromotionsViewModel($categories, $productsToShow);


        return $this->render('@App/Listing Products/promotionsView.html.twig', ["model" => $model]);
    }
}
