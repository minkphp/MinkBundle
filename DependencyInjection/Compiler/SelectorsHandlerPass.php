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

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Selectors handler compilation pass. Registers all available Mink selector engines.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class SelectorsHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('mink.selectors_handler')) {
            return;
        }

        $handlerDefinition = $container->getDefinition('mink.selectors_handler');
        foreach ($container->findTaggedServiceIds('mink.selector') as $id => $attributes) {
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
