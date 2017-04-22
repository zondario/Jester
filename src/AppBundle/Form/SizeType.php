<?php

namespace AppBundle\Form;

use AppBundle\Entity\Size;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SizeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class"=>Size::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_size_type';
    }
}
