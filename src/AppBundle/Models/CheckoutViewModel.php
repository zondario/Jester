<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/17/2017
 * Time: 5:42 PM
 */

namespace AppBundle\Models;


class CheckoutViewModel
{
    private $categories;
    private $orders;

    /**
     * CheckoutViewModel constructor.
     * @param $categories
     * @param $orders
     */
    public function __construct($categories, $orders)
    {
        $this->categories = $categories;
        $this->orders = $orders;
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
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

}