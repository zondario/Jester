<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{


    function __construct()
    {
        $this->images = new ArrayCollection();
        $this->stocks = new ArrayCollection();
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
     * @var string
     * @Assert\Length(min="5",minMessage="Sorry ne stava taka ")
     * @ORM\Column(name="name", type="string", l
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
     * @ORM\Column(name="description", type="string", length=2000)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=2000)
     */
    private $slug;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category",inversedBy="products")
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     */
    private $category;


    /**
     * @var Image[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image",mappedBy="product")
     */
    private $images;

    /**
     * @var string
     *
     * @ORM\Column(name="preview_details", type="string",nullable=true)
     */
    private $previewDetails;

    /**
     * @var Stock[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Stock",mappedBy="product")
     */
    private $stocks;

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
     * @return Category
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

    /**
     * @return Image[]|ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return Stock[]|ArrayCollection
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    /**
     * @param Stock[]|ArrayCollection $stocks
     */
    public function setStocks($stocks)
    {
        $this->stocks = $stocks;
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
}

