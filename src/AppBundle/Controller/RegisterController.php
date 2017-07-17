<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegisterController extends Controller
{
    /**
     * @Route("/registration",name="appRegistration")
     */
    public function registerAction(Request $request)
    {
            $user = new User();

            $form = $this->createForm(RegistrationType::class,$user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user->setEnabled(true);
                $user->addRole("ROLE_USER");
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $this->get('session')->set('_security_main', serialize($token));
                $this->addFlash("success","base.after_register");
                return $this->redirectToRoute("homepage");

            }
            return $this->render('AppBundle:Registration:register.html.twig', array(
                'form' => $form->createView(),
            ));
    }
}