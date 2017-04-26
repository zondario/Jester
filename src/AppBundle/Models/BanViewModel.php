<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/26/2017
 * Time: 9:38 PM
 */

namespace AppBundle\Models;


class BanViewModel
{
    private $categories;

    /**
     * BanViewModel constructor.
     * @param $categories
     */
    public function  __construct($categories)
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