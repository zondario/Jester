<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/21/2017
 * Time: 7:22 PM
 */

namespace AppBundle\Models;


class AdminPanelViewModel
{
    private $categories;

    function __construct($categories)
    {
        $this->setCategories($categories);
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

}