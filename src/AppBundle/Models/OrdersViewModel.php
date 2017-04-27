<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/25/2017
 * Time: 9:29 PM
 */

namespace AppBundle\Models;


use AppBundle\Entity\ProductOrder;
use Knp\Component\Pager\Paginator;

class OrdersViewModel
{
    private $categories;
    private $orders;
    private $status;


    /**
     * OrdersViewModel constructor.
     * @param $categories
     * @param $orders
     * @param $status
     */
    public function __construct($categories,$orders,$status)
    {
        $this->categories = $categories;
        $this->orders=$orders;
        $this->status=$status;
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
     * @return Paginator|ProductOrder[]
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

    /**
     * @return mixed
     */
    public function     getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}