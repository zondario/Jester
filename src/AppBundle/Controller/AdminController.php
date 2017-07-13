<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Category;

use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductOrder;
use AppBundle\Entity\Promotion;


use AppBundle\Entity\Status;
use AppBundle\Entity\Stock;
use AppBundle\Entity\User;
use AppBundle\Form\ImageType;
use AppBundle\Models\AdminPanelViewModel;
use AppBundle\Models\BanViewModel;
use AppBundle\Models\OrdersViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Translator;

class AdminController extends Controller
{
    const IMAGES_VIEW_DIRECTORY = "../../images/productImages/";


    /**
     * @Route("/admin",name="adminPanel")
     */
    public function adminPanel()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $model = new AdminPanelViewModel($categories);
        return $this->render('@App/admin/adminPanel.html.twig', array('model' => $model));
    }


    /**
     * @Route("/admin/promote/stock/{id}", name="promoteStock", methods={"POST"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function promoteStock(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository(Stock::class)->findOneBy(["id" => $id]);
        $promotion = $em->getRepository(Promotion::class)->findOneBy(["id" => $request->get("promotion")]);
        if ($stock != null && $promotion != null) {
            $stock->getPromotions()->add($promotion);
            $promotion->getStocks()->add($stock);
            $em->persist($stock);
            $em->persist($promotion);
            $em->flush();
        }
        return $this->redirectToRoute("detailsView", ["id" => $id]);

    }

    /**
     * @Route("/admin/promote/product/{id}", methods={"POST"},name="promoteProduct")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function promoteProduct($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);
        /** @var Stock[] $stocks */
        $stocks = $product->getStocks();
        $promotion = $em->getRepository(Promotion::class)
            ->findOneBy(["id" => $request->get("productPromotion")]);
        if ($product != null && $promotion != null) {
            foreach ($stocks as $stock) {
                if (!in_array($promotion, $stock->getPromotions()->toArray())) {
                    $stock->getPromotions()->add($promotion);
                    $promotion->getStocks()->add($stock);
                    $em->persist($stock);
                    $em->persist($product);
                }
            }
            $em->flush();
            $this->addFlash("success", $product->getName() . " successfully promoted");
        }
        return $this->redirect($request->server->get("HTTP_REFERER"));
    }

    /**
     * @Route("/admin/add/image/{productId}",name="addImage")
     * @param $productId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addImage($productId, Request $request)
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image, ["csrf_protection" => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository(Product::class)->findOneBy(["id" => $productId]);
            $image = $this->get("app.image_uploader")->upload($image);
            $image->setProduct($product);
            $em->persist($image);
            $em->flush();
            $this->addFlash("success", "Image added");
        } elseif ($form->getErrors(true)->count() > 0) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash("danger", $error->getMessage());
            }
        }

        return $this->redirect($request->server->get("HTTP_REFERER"));
    }

    /**
    * @Route("/admin/delete/image/{imageId}",name="deleteImage")
    *
    **/
    public function delImage($imageId)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository(Image::class)->findOneById($imageId);
        $image = $this->get("app.image_uploader")->delete($image);
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/admin/ban",name="banUser")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function banUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $currUser */
        $currUser = $this->getUser();
        $model = new BanViewModel($em->getRepository(Category::class)->findAll());

        if ($request->get("user")) {
            /** @var User $user */
            $user = $em->getRepository(User::class)->findByUserNameOrFullName($request->get("user"));
            if ($user) {
                if ($this->isGranted("ROLE_ADMIN")) {
                    if($this->isGranted("ROLE_SUPER_ADMIN"))
                    {
                        $user->setEnabled(false);
                        $em->persist($user);
                        $em->flush();
                        $this->addFlash("success", $request->get("user") . " successfully banned!");
                    }else {
                        $this->addFlash("error", "Admins cannot be banned");
                    }

                } else
                {
                    $user->setEnabled(false);
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash("success", $request->get("user") . " successfully banned!");
                }

            } else {
                $this->addFlash("danger", "User not found");

            }

        }
        return $this->render("@App/admin/ban.html.twig", ["model" => $model]);
    }

    /**
     * @Route("admin/promote",name="promoteUser")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function promoteUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $model = new BanViewModel($em->getRepository(Category::class)->findAll());

        if ($request->get("user")) {
            $user = $em->getRepository(User::class)->findByUserNameOrFullName($request->get("user"));
            if ($user) {
                if (!in_array("ROLE_ADMIN", $user->getRoles())) {
                    $user->addRole("ROLE_ADMIN");
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash("success", $request->get("user") . " successfully promoted!");
                } else {
                    $this->addFlash("error", "User is already Admin");
                }

            } else {
                $this->addFlash("danger", "User not found");
            }

        }
        return $this->render("@App/admin/promote.html.twig", ["model" => $model]);
    }


    /**
     * @Route("admin/send-mail",name="sendMail")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendMail()
    {
        /** @var Translator $trans */
        $trans = $this->get("translator");
        $var = $trans->trans("test",[],"AppBundle");
        var_dump($var);exit;

    }

}
