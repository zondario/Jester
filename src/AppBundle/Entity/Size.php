<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Size
 *
 * @ORM\Table(name="sizes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SizeRepository")
 * @UniqueEntity("name",message="Already exists")
 */
class Size
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var Stock[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Stock",mappedBy="size")
     */
    private $stocks;


    function __construct()
    {
        $this->stocks = new ArrayCollection();
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
     * @return Size
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
}

