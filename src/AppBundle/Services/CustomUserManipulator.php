<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/20/2017
 * Time: 1:14 AM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Product;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Stock;
use AppBundle\Entity\User;
use AppBundle\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;


class CustomUserManipulator
{
    private $em;
    /**
     * @var UserManager
     */
    private $um;
    /**
     * @var PasswordEncoderInterface
     */
    private $passEncoder;
    /**
     * PromotionService constructor.
     */
    public function __construct(EntityManager $em, UserManager $userManager, UserPasswordEncoder $passwordEncoder)
    {
        $this->em = $em;
        $this->um = $userManager;
        $this->passEncoder = $passwordEncoder;
    }

    public function create($full_name, $username,$email, $phone, $password, $superadmin)
    {
        $user = new User();
        $user->setFullName($full_name);
        $user->setPhone($phone);
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setEnabled(true);
        $user->setPassword($this->passEncoder->encodePassword($user,$password, $user->getSalt()));
        $user->setSuperAdmin((bool) $superadmin);
        $this->um->updateUser($user);
        $this->em->persist($user);
        $this->em->flush();


    }
}