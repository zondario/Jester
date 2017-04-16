<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;


/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255 , nullable=false)
     */
    private $phone;

    /**
     * @var  ProductOrder[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductOrder",mappedBy="user")
     * */
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
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }
    /**
     * Overriding Fos User class due to impossible to set default role ROLE_USER
     * @see User at line 138
     * @link https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Model/User.php#L138
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
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

}

