<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Comparable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Promotion
 *
 * @ORM\Table(name="promotions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PromotionRepository")
 */
class Promotion implements Comparable
{
    function __construct()
    {
        $this->stocks=new ArrayCollection();
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
     * @var int
     *
     * @ORM\Column(name="percentage", type="integer")
     */
    private $percentage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startsOn", type="date")
     */
    private $startsOn;

    /**
     * @var \DateTime
     * @ORM\Column(name="endsOn", type="date")
     */
    private $endsOn;

    /** @var  Stock[]
     *  @ORM\ManyToMany(targetEntity="AppBundle\Entity\Stock",mappedBy="promotions")
     *  @ORM\JoinColumn(name="stock_id",referencedColumnName="id")
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
     * Set percentage
     *
     * @param integer $percentage
     *
     * @return Promotion
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * Get percentage
     *
     * @return int
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Set startsOn
     *
     * @param \DateTime $startsOn
     *
     * @return Promotion
     */
    public function setStartsOn($startsOn)
    {
        $this->startsOn = $startsOn;

        return $this;
    }

    /**
     * Get startsOn
     *
     * @return \DateTime
     */
    public function getStartsOn()
    {
        return $this->startsOn;
    }

    /**
     * Set endsOn
     *
     * @param string $endsOn
     *
     * @return Promotion
     */
    public function setEndsOn($endsOn)
    {
        $this->endsOn = $endsOn;

        return $this;
    }

    /**
     * Get endsOn
     *
     * @return string
     */
    public function getEndsOn()
    {
        return $this->endsOn;
    }

    /**
     * @return Stock[]|ArrayCollection
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    /**
     * @param Stock[] $stocks
     */
    public function setStocks($stocks)
    {
        $this->stocks = $stocks;
    }

    /**
     * Compares the current object to the passed $other.
     *
     * Returns 0 if they are semantically equal, 1 if the other object
     * is less than the current one, or -1 if its more than the current one.
     *
     * This method should not check for identity using ===, only for semantical equality for example
     * when two different DateTime instances point to the exact same Date + TZ.
     *
     * @param Promotion $other
     *
     * @return int
     */
    public function compareTo($other)
    {
        return $this->getPercentage() > $other->getPercentage();
    }
}

