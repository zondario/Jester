<?php

namespace AppBundle\Form;

use AppBundle\Entity\Color;
use AppBundle\Entity\Size;
use AppBundle\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("size",EntityType::class,["class"=>Size::class,"choice_label"=>"name"])
            ->add("color",EntityType::class,["class"=>Color::class,"choice_label"=>"name"])
            ->add("quantity")
            ->add("product",ProductType::class,array("constraints"=>new Valid()))
            ->add('image', ImageType::class, array('mapped' => false,"constraints"=>new Valid()));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class"=>Stock::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_stock_type';
    }
}
