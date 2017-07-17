<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/20/2017
 * Time: 1:14 AM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Image;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\User;
use Symfony\Bridge\Twig\TwigEngine;



class AppMailerService
{
    private $mailer;
    private $translator;
    private $templating;

    /**
     * ImageUploaderService constructor.
     * @param $dir
     * @param $imagesViewDir
     */
    public function __construct( \Swift_Mailer $mailer, $translator, TwigEngine $templating)
    {

        $this->mailer = $mailer;
        $this->translator =$translator;
        $this->templating = $templating;

    }


    /**
     * @param $orders ProductOrder[]
     * @param $user User
     * @internal param Image $image
     */
    public function sendEmailsOnOrder($orders, $user)
    {

       $message = \Swift_Message::newInstance($this->translator->trans("emails.order.subject",[],"AppBundle"))
           ->setFrom("deathsblood444@gmail.com")
           ->setTo("ivo.cenov1@gmail.com")
           ->setBody($this->templating->render("AppBundle:emails:email_orders.html.twig", ["orders" => $orders, "user" => $user]),"text/html");
       $this->mailer->send($message);
    }



}