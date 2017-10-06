<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Stock
 *
 * @ORM\Table(name="stocks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StockRepository")
 * @UniqueEntity(
 *     fields={"color", "size", "product"},
 *     message="You cannot have the same color and size on the same product"
 * )
 */
class Stock
{
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $this->setSlug(strtolower($this->getProduct()->getSlug()."-".$this->getSize()->getName()."-".$this->getColor()->getName()));
    }
    function __construct()
    {
        $this->promotions = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return Size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param Size $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param Color $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    /**
     * @var Size
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Size",inversedBy="stocks")
     */
    private $size;

    /**
     * @var Color
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Color", inversedBy="stocks")
     */
    private $color;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product",inversedBy="stocks")
     */
    private $product;

    /** @var  Product[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Promotion",inversedBy="stocks")
     * @ORM\JoinColumn(name="promotion_id",referencedColumnName="id")
     */
    private $promotions;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=2000)
     */
    private $slug;


    /**
     * @var int
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;
    /**
     * @var  ProductOrder[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductOrder",mappedBy="stock")
     */
    private $orders;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ProductOrder[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param ProductOrder[] $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return bool
     */
    public function isIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return Promotion[]|ArrayCollection
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * @param Product[] $promotions
     */
    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;
    }
}

