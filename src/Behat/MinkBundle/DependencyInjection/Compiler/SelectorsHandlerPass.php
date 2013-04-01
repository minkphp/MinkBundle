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
 * Selectors handler compilation pass. Registers all avaiable Mink selector engines.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class SelectorsHandlerPass implements CompilerPassInterface
{
    /**
     * Processes container.
     *
     * @param   Symfony\Component\DependencyInjection\ContainerBuilder  $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('behat.mink.selectors_handler')) {
            return;
        }

        $handlerDefinition = $container->getDefinition('behat.mink.selectors_handler');
        foreach ($container->findTaggedServiceIds('behat.mink.selector') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (isset($attribute['alias']) && $alias = $attribute['alias']) {
                    $handlerDefinition->addMethodCall(
                        'registerSelector', array($alias, new Reference($id))
                    );
                }
            }
        }
    }
}
