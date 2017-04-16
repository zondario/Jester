<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove("email")->remove("username")->remove("plainPassword");

        $builder
            ->add('email', EmailType::class)
            ->add('username', null)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'invalid_message' => 'Password Missmatch',
            ))->add("fullName",TextType::class,array("label"=>"Full Name",'attr'=>array('class'=>"form-control")))
            ->add("phone",TextType::class);
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}