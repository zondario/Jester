<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/17/2017
 * Time: 5:33 PM
 */

namespace AppBundle\Models;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;

class CategoryViewModel
{
    /** @var  $activeCategory Category */
    private $activeCategory;

    /** @var  $categories Category[]|ArrayCollection */
    private $categories;

    /** @var  $productsToDisplay Product[]|ArrayCollection */
    private $productsToDisplay;

    /**
     * @return Category
     */
    public function getActiveCategory()
    {
        return $this->activeCategory;
    }

    /**
     * @param Category $activeCategory
     */
    public function setActiveCategory(Category $activeCategory)
    {
        $this->activeCategory = $activeCategory;
    }

    /**
     * @return Category[]|ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[]|ArrayCollection $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getProductsToDisplay()
    {
        return $this->productsToDisplay;
    }

    /**
     * @param Product[]|ArrayCollection $productsToDisplay
     */
    public function setProductsToDisplay($productsToDisplay)
    {
        $this->productsToDisplay = $productsToDisplay;
    }

    /**
     * CategoryViewModel constructor.
     * @param Category $activeCategory
     * @param Category[] $categories
     * @param Product[] $productsToDisplay
     */
    public function __construct($activeCategory, $categories, $productsToDisplay)
    {
        $this->activeCategory = $activeCategory;
        $this->categories = $categories;
        $this->productsToDisplay = $productsToDisplay;
    }


}