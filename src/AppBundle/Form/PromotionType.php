<?php

namespace AppBundle\Form;

use AppBundle\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("percentage")
            ->add("startsOn", null, ["label" => "Start Date:"])
            ->add("endsOn", null, ["label" => "Ends on"]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class" => Promotion::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_promotion_type';
    }
}
