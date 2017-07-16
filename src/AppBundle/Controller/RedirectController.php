<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Category;

use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Promotion;


use AppBundle\Entity\Status;
use AppBundle\Entity\Stock;
use AppBundle\Entity\User;
use AppBundle\Form\ImageType;
use AppBundle\Models\AdminPanelViewModel;
use AppBundle\Models\BanViewModel;
use AppBundle\Models\OrdersViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Translator;

class RedirectController extends Controller
{
    


    /**
     * @Route("/", name="redirect")
     */
    public function redirAction(Request $request)
    {
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/route", name="redirectCulture")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectCulture(Request $request)
    {
        $url = $request->headers->get("referer");

        $url = preg_replace("/\/bg\/|\/en\//","/".$request->get("culture")."/",$url);
        return $this->redirect($url);
    }

}
