<?php

namespace Behat\MinkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/*
 * This file is part of the Behat\MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * SahiDriver container compilation pass. Checks sahi SID and generates new if it's null.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class SahiDriverPass implements CompilerPassInterface
{
    /**
     * Processes container.
     *
     * @param   Symfony\Component\DependencyInjection\ContainerBuilder  $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('behat.mink.driver.sahi')) {
            return;
        }

        if (null === $container->getParameter('behat.sahi.sid')) {
            $container->setParameter('behat.sahi.sid', uniqid());
        }
    }
}
