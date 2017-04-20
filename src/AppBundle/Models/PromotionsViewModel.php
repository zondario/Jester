<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/19/2017
 * Time: 10:38 PM
 */

namespace AppBundle\Models;


class PromotionsViewModel
{
    private $categories;
    private $products;

    /**
     * PromotionsViewModel constructor.
     * @param $categories
     * @param $products
     */
    public function __construct($categories, $products)
    {
        $this->categories = $categories;
        $this->products = $products;
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
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

}