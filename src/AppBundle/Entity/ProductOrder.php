<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOrder
 *
 * @ORM\Table(name="product_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductOrderRepository")
 */
class ProductOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @var  Stock $stock
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\Stock",inversedBy="orders")
     *@ORM\JoinColumn(name="stock_id",referencedColumnName="id")
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Status")
     * @ORM\JoinColumn(name="status",referencedColumnName="id")
     */
    private $status;

    /**
     * @var  User $user
     *
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="orders")
     *@ORM\JoinColumn(name="user",referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=255)
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="orderedOn", type="date",nullable=true)
     */
    private $orderedOn;

    /** @var  int
     *@ORM\Column(name="calculated_single_price",type="integer")
     */
    private $calculatedSinglePrice;
    /**
     * @ORM\Column(name="final_price", type="decimal")
     */
    private $finalPrice;

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
     * Set quantity
     *
     * @param string $quantity
     *
     * @return ProductOrder
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set orderedOn
     *
     * @param \DateTime $orderedOn
     *
     * @return ProductOrder
     */
    public function setOrderedOn($orderedOn)
    {
        $this->orderedOn = $orderedOn;

        return $this;
    }

    /**
     * Get orderedOn
     *
     * @return \DateTime
     */
    public function getOrderedOn()
    {
        return $this->orderedOn;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return ProductOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getFinalPrice()
    {
        return $this->finalPrice;
    }

    /**
     * @param mixed $finalPrice
     */
    public function setFinalPrice($finalPrice)
    {
        $this->finalPrice = $finalPrice;
    }

    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getCalculatedSinglePrice()
    {
        return $this->calculatedSinglePrice;
    }

    /**
     * @param int $calculatedSinglePrice
     */
    public function setCalculatedSinglePrice($calculatedSinglePrice)
    {
        $this->calculatedSinglePrice = $calculatedSinglePrice;
    }


}

