<?php

namespace Behat\MinkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder,
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
 * Behat\Mink container compilation pass. Replaces test session listener for real session listener.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class TestSessionListenerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ('test' === $container->getParameterBag()->get('kernel.environment')) {
            $sessionListenerClass = $container->getParameter('session_listener.class');
            $container->setParameter('test.session.listener.class', $sessionListenerClass);
        }
    }
}
