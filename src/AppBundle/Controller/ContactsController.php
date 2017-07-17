<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;

use AppBundle\Models\CategoryViewModel;
use AppBundle\Models\HomeViewModel;
use AppBundle\Models\ListContactsViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ContactsController extends Controller
{
  /**
   * @Route("/admin/list/contacts", name="list_contacts")
   */
    public function listContacts()
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepo = $em->getRepository(Category::class);
        $categories = $categoryRepo->findAll();
        $model = new ListContactsViewModel($categories);
        return $this->render("@App/admin/list_contacts.html.twig",["model" => $model]);
    }

}
