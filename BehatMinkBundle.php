<?php

namespace Behat\MinkBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\HttpKernel\Bundle\Bundle;

use Behat\MinkBundle\DependencyInjection\Compiler\MinkPass,
    Behat\MinkBundle\DependencyInjection\Compiler\SelectorsHandlerPass,
    Behat\MinkBundle\DependencyInjection\Compiler\SahiDriverPass;

/*
 * This file is part of the Behat\MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * MinkBundle.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class BehatMinkBundle extends Bundle
{
    /**
     * Registers compilation passes.
     *
     * @param   Symfony\Component\DependencyInjection\ContainerBuilder  $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MinkPass());
        $container->addCompilerPass(new SelectorsHandlerPass());
        $container->addCompilerPass(new SahiDriverPass());
    }
}
