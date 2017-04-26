<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/27/2017
 * Time: 1:07 AM
 */

namespace AppBundle\Models;


class SearchViewModel
{
    private $categories;
    private $productsToShow;
    private $terms;

    /**
     * SearchViewModel constructor.
     * @param $categories
     * @param $productsToShow
     * @param $terms
     */
    public function __construct($categories, $productsToShow,$terms)
    {
        $this->categories = $categories;
        $this->productsToShow = $productsToShow;
        $this->terms = $terms;
    }


    /**
     * @return mixed
     */
    public function getProductsToShow()
    {
        return $this->productsToShow;
    }

    /**
     * @param mixed $productsToShow
     */
    public function setProductsToShow($productsToShow)
    {
        $this->productsToShow = $productsToShow;
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
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * @param mixed $terms
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
    }

}