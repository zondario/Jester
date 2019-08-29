<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/20/2017
 * Time: 1:14 AM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Contact;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\User;
use AppBundle\Repository\ContactRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;


class AppMailerService
{
    private $mailer;
    private $translator;
    private $templating;
    private $em;

    /**
     * ImageUploaderService constructor.
     * @param \Swift_Mailer $mailer
     * @param Translator $translator
     * @param TwigEngine $templating
     * @param EntityManager $em
     * @internal param $dir
     * @internal param $imagesViewDir
     */
    public function __construct( \Swift_Mailer $mailer,TranslatorInterface $translator, TwigEngine $templating,EntityManager $em)
    {

        $this->mailer = $mailer;
        $this->translator =$translator;
        $this->templating = $templating;
        $this->em = $em;
    }


    /**
     * @param $orders ProductOrder[]
     * @param $user User
     * @internal param Image $image
     */
    public function sendEmailsOnOrder($orders, $user)
    {
        /** @var ContactRepository $repo */
        $repo = $this->em->getRepository(Contact::class);
        $contacts = $repo->findAll();
       $message = \Swift_Message::newInstance($this->translator->trans("emails.order.subject",[],"AppBundle"))
           ->setFrom("i.cenov@stenik.bg")
           ->setTo(implode(", ",$contacts))
           ->setBody($this->templating->render("AppBundle:emails:email_orders.html.twig", ["orders" => $orders, "user" => $user]),"text/html");
       $this->mailer->send($message);
    }



}