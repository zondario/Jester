<?php

namespace AppBundle\Form;

use AppBundle\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("url",FileType::class,["constraints"=> new File([
            'maxSize' => '5M',
            'mimeTypes' => [
                'image/jpeg',
                'image/png',
                'image/jpg'
            ]])]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class"=>Image::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_image_type';
    }
}
