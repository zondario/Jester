    <?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/17/2017
 * Time: 5:38 PM
 */

namespace AppBundle\Models;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Stock;

class DetailsViewModel
{
    private $categories;
    private $stock;
    private $product;
    private $stocksToShow;
    private $stockFinalPrice;
    private $stockActivePromotion;
    private $promotionsToDisplay;
    private $productPromotionsToDisplay;

    /**
     * @return Category
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return Stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * DetailsViewModel constructor.
     * @param $categories
     * @param $stock
     * @param $product
     * @param $stocksToShow
     * @param $stockFinalPrice
     * @param $stockActivePromotion
     * @param $promotionsToDisplay
     * @param $productPromotionsToDisplay
     */
    public function __construct($categories, $stock, $product, $stocksToShow, $stockFinalPrice, $stockActivePromotion, $promotionsToDisplay, $productPromotionsToDisplay)
    {
        $this->categories = $categories;
        $this->stock = $stock;
        $this->product = $product;
        $this->stocksToShow = $stocksToShow;
        $this->stockFinalPrice = $stockFinalPrice;
        $this->stockActivePromotion = $stockActivePromotion;
        $this->promotionsToDisplay = $promotionsToDisplay;
        $this->productPromotionsToDisplay = $productPromotionsToDisplay;

    }

    /**
     * @return mixed
     */
    public function getStocksToShow()
    {
        return $this->stocksToShow;
    }

    /**
     * @param mixed $stocksToShow
     */
    public function setStocksToShow($stocksToShow)
    {
        $this->stocksToShow = $stocksToShow;
    }

    /**
     * @return mixed
     */
    public function getStockFinalPrice()
    {
        return $this->stockFinalPrice;
    }

    /**
     * @param mixed $stockFinalPrice
     */
    public function setStockFinalPrice($stockFinalPrice)
    {
        $this->stockFinalPrice = $stockFinalPrice;
    }

    /**
     * @return mixed
     */
    public function getStockActivePromotion()
    {
        return $this->stockActivePromotion;
    }

    /**
     * @param mixed $stockActivePromotion
     */
    public function setStockActivePromotion($stockActivePromotion)
    {
        $this->stockActivePromotion = $stockActivePromotion;
    }

    /**
     * @return mixed
     */
    public function getPromotionsToDisplay()
    {
        return $this->promotionsToDisplay;
    }

    /**
     * @param mixed $promotionsToDisplay
     */
    public function setPromotionsToDisplay($promotionsToDisplay)
    {
        $this->promotionsToDisplay = $promotionsToDisplay;
    }

    /**
     * @return mixed
     */
    public function getProductPromotionsToDisplay()
    {
        return $this->productPromotionsToDisplay;
    }

    /**
     * @param mixed $productPromotionsToDisplay
     */
    public function setProductPromotionsToDisplay($productPromotionsToDisplay)
    {
        $this->productPromotionsToDisplay = $productPromotionsToDisplay;
    }

}