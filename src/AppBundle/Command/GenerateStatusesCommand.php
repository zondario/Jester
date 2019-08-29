<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Color;
use AppBundle\Entity\Status;
use AppBundle\Services\CustomUserManipulator;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Util\UserManipulator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;


class GenerateStatusesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {


        $this
            ->setName('app:generate_statuses')
            ->setDescription('Generates the statuses');


    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var EntityManager $em */
        $em = $this->getContainer()->get("doctrine.orm.entity_manager");
        $status = new Status();
        $status->setName("added");
        $em->persist($status);
        $status = new Status();
        $status->setName("requested");
        $em->persist($status);
        $status = new Status();
        $status->setName("reviewed");
        $em->persist($status);
        $status = new Status();
        $status->setName("sent");
        $em->persist($status);
        $status = new Status();
        $status->setName("refused");
        $em->persist($status);
        $status = new Status();
        $status->setName("confirmed");
        $em->persist($status);
        $em->flush();
//        $files = new Filesystem();
//        $string = file_get_contents("colors.txt");
//        $arr = explode(",", $string);
//        foreach ($arr as $item) {
//            $curr = new Color();
//            $curr->setName($item);
//            if($curr->getName() != "Blue" && $curr->getName() != "Azure")
//            $em->persist($curr);
//        }
//        $em->flush();
    }
}
