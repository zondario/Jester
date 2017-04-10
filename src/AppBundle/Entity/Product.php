<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Product
 *
 * @ORM\Table(name="products", uniqueConstraints={@UniqueConstraint(name="product_unique", columns={"name", "color_id","size_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=11, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;
    /** @var  Category
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\Category",inversedBy="products")
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     */
    private $category;


    /**
     * @var string
     *@ORM\Column(name="preview_details", type="string")
     */
    private $previewDetails;
    /** @var  Color
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Color",inversedBy="products")
     * @ORM\JoinColumn(name="color_id",referencedColumnName="id")
     *
     */
    private $color;
    /** @var  Size
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\Size",inversedBy="products")
     * @ORM\JoinColumn(name="size_id",referencedColumnName="id")
     */
    private $size;

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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getPreviewDetails()
    {
        return $this->previewDetails;
    }

    /**
     * @param mixed $previewDetails
     */
    public function setPreviewDetails($previewDetails)
    {
        $this->previewDetails = $previewDetails;
    }
}

