<?php
/**
 * Created by PhpStorm.
 * User: Notebook
 * Date: 4/20/2017
 * Time: 1:14 AM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Image;


class ImageUploaderService
{
    private $dir;
    private $imagesViewDir;
    private $em;

    /**
     * ImageUploaderService constructor.
     * @param $dir
     * @param $imagesViewDir
     */
    public function __construct($dir, $imagesViewDir, $em)
    {
        $this->dir = $dir;
        $this->imagesViewDir = $imagesViewDir;
        $this->em = $em;
    }


    /**
     * @param Image $image
     * @return Image
     */
    public function upload($image)
    {

        $product_image = $image;
        $imageName = md5(uniqid()) . '.' . $image->getUrl()->guessExtension();
        $image->getUrl()->move($this->getDir(), $imageName);
        $product_image->setUrl($this->getImagesViewDir() . $imageName);
        return $product_image;
    }

    public function delete($image)
    {
        unlink($this->getDir().substr($image->getUrl(),26));
        $this->em->remove($image);
        $this->em->flush();
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