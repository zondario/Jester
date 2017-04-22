<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/20/2017
 * Time: 1:14 AM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Stock;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;

class ImageUploaderService
{
    private $dir;
    private $imagesViewDir;

    /**
     * ImageUploaderService constructor.
     * @param $dir
     * @param $imagesViewDir
     */
    public function __construct($dir,$imagesViewDir)
    {
        $this->dir = $dir;
        $this->imagesViewDir=$imagesViewDir;
    }


    /**
     * @param Image $image
     * @return Image
     */
    public function upload($image)
    {

        $product_image = $image;
        $imageName = md5(uniqid()).'.'.$image->getUrl()->getExtension();
        $image->getUrl()->move($this->getDir(),$imageName);
        $product_image->setUrl($this->getImagesViewDir() .$imageName);
        return $product_image;
    }

    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return mixed
     */
    public function getImagesViewDir()
    {
        return $this->imagesViewDir;
    }

}