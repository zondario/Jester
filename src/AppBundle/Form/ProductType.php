<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, ["class" => Category::class, "choice_label" => "name"])
            ->add("name")
            ->add("price", MoneyType::class)
            ->add("description");

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_product_type';
    }
}
