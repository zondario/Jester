<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class"=>Category::class]);

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_category_type';
    }
}
