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
 * Behat\Mink container compilation pass. Registers all available in controller Mink drivers.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class MinkPass implements CompilerPassInterface
{
    /**
     * Processes container.
     *
     * @param   Symfony\Component\DependencyInjection\ContainerBuilder  $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('behat.mink')) {
            return;
        }

        $minkDefinition = $container->getDefinition('behat.mink');
        $defaultDriver  = $container->getParameter('behat.mink.default_driver');

        foreach ($container->findTaggedServiceIds('behat.mink.driver') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (isset($attribute['alias']) && $alias = $attribute['alias']) {
                    $isDefault = $defaultDriver == $alias;
                    $minkDefinition->addMethodCall(
                        'registerDriver', array($alias, new Reference($id), $isDefault)
                    );
                }
            }
        }
    }
}
