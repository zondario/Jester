<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/27/2017
 * Time: 7:57 PM
 */

namespace AppBundle\Models;


class HomeViewModel
{
    private $categories;

    /**
     * PromotionsViewModel constructor.
     * @param $categories
     * @param $products
     */
    public function __construct($categories)
    {
        $this->categories = $categories;

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