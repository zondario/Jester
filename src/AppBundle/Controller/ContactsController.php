<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;

use AppBundle\Entity\Contact;
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
        $contactsRepo = $em->getRepository(Contact::class);
        $contacts = $contactsRepo->findAll();
        $model = new ListContactsViewModel($categories, $contacts);
        return $this->render("@App/admin/list_contacts.html.twig", ["model" => $model]);
    }
    /**
     * @Route("/admin/edit/contact/{id}", name="edit_contact")
     */
    public function editContact($id)
    {
        $em = $this->getDoctrine()->getManager();
        $contactsRepo = $em->getRepository(Contact::class);
        $this->redirectToRoute("list_contacts");
    }
    /**
     * @Route("/admin/delete/contact/{id}", name="delete_contact")
     */
    public function deleteContact($id)
    {
        $em = $this->getDoctrine()->getManager();
        $contactsRepo = $em->getRepository(Contact::class);
        $this->redirectToRoute("list_contacts");
    }

}
