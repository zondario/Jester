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

use AppBundle\Services\CustomUserManipulator;
use FOS\UserBundle\Util\UserManipulator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;



class CreateSuperAdminCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {


        $this
            ->setName('app:createSA')
            ->setDescription('Creates super admin for the site')
            ->addArgument("username", InputArgument::REQUIRED, "Username")
            ->addArgument("email", InputArgument::REQUIRED, "Email")
            ->addArgument("password", InputArgument::REQUIRED, "Pass")
            ->addArgument("full_name", InputArgument::REQUIRED, "full name")
            ->addArgument("phone", InputArgument::REQUIRED, "phone")
            ->setHelp("USERNAME  EMAIL  PASS  FULL_NAME  PHONE");

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument("username");
        /** @var CustomUserManipulator $userManipulator */
        $userManipulator = $this->getContainer()->get("app.custom_user_manipulator");
        $userManipulator->create($input->getArgument("full_name"),$input->getArgument("username"),$input->getArgument("email"),$input->getArgument("phone"),$input->getArgument("password"),true);
    }
}
