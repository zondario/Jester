<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ["label" => "form.email"])
            ->add('username', null,["label" => "form.username"])
            ->add('plainPassword', RepeatedType::class, array(
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => "form.password_confirmation"],
                'type' => PasswordType::class,
                'invalid_message' => 'Password Missmatch',
            ))
            ->add("fullName", TextType::class, array("label" => "form.full_name", 'attr' => array('class' => "form-control")))
            ->add("phone", TextType::class, ['label' => "form.phone"]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'csrf_token_id' => 'registration',
            'translation_domain' => "AppBundle",
            'intention' => 'registration',
        ));
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