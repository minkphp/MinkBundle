<?php

/*
 * This file is part of the Behat MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Behat\MinkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Behat\Mink container compilation pass. Replaces test session listener for real session listener.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class TestSessionListenerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $sessionListenerClass = $container->getParameter('session_listener.class');
        $container->setParameter('test.session.listener.class', $sessionListenerClass);
    }
}
