<?php

namespace AppBundle\Form;

use AppBundle\Entity\Color;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name");

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class" => Color::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_color_type';
    }
}
