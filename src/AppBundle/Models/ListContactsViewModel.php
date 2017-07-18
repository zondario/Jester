<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/27/2017
 * Time: 1:07 AM
 */

namespace AppBundle\Models;


class ListContactsViewModel
{
    private $categories;
    private $contacts;



    /**
     * SearchViewModel constructor.
     * @param $categories
     * @param $productsToShow
     * @param $terms
     */
    public function __construct($categories, $contacts)
    {
        $this->categories = $categories;
        $this->contacts = $contacts;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return mixed
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param mixed $contacts
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    }

}