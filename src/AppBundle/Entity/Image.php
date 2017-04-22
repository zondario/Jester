<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
/**
 * Image
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 *
 */
class Image
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
     * @var mixed
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     *
     */
    private $url;

    /**
     * @var Product $product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product",inversedBy="images")
     *
     */
    private $product;

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
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

}

