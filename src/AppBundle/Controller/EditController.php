<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends Controller
{
    /**
     * @Route("/admin/edit/{class}/{id}",name="editProduct")
     * @param $class
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function editProduct($class, $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $class = ucfirst(strtolower($class));
        $namespace = "AppBundle\\Entity\\";
        $fullname = $namespace . $class;
        $object = $em->getRepository($fullname)->findOneBy(["id" => $id]);

        if ($object == null) {
            return $this->redirectToRoute("homepage");
        }
        $form = $this->createForm("AppBundle\\Form\\" . $class . "Type", $object);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($object);
            $em->flush();
            $this->addFlash("success", $class . " successfully edited");
            return $this->redirectToRoute("homepage");
        }

        return $this->render("@App/admin/edit" . $class . ".html.twig", ["form" => $form->createView()]);
    }
}
