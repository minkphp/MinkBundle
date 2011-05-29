<?php

namespace Behat\MinkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator;

/*
 * This file is part of the Behat\MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Symfony2 Behat\Mink extension.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class BehatMinkExtension extends Extension
{
    /**
     * Loads the services based on your application configuration.
     *
     * @param array $configs
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        if (!$this->isMinkConfigIncluded($configs)) {
            return;
        }

        $processor      = new Processor();
        $configuration  = new Configuration();
        $config         = $processor->processConfiguration($configuration, $configs);

        $loader = $this->getFileLoader($container);
        $loader->load('mink.xml');

        if (isset($config['goutte'])) {
            $loader->load('sessions/goutte.xml');
        }
        if (isset($config['sahi'])) {
            $loader->load('sessions/sahi.xml');
        }

        foreach ($config as $ns => $tlValue) {
            if (!is_array($tlValue)) {
                $container->setParameter("behat.mink.$ns", $tlValue);
            } else {
                foreach ($tlValue as $name => $value) {
                    $container->setParameter("behat.mink.$ns.$name", $value);
                }
            }
        }

        $minkReflection = new \ReflectionClass('Behat\Mink\Mink');
        $minkLibPath    = realpath(dirname($minkReflection->getFilename()) . '/../../../');
        $container->setParameter('mink.paths.lib', $minkLibPath);
    }

    /**
     * Whether or not any mink config was included.
     *
     * Used so that if mink isn't specifically enabled in the config, it's
     * not loaded at all.
     */
    private function isMinkConfigIncluded(array $configs)
    {
        foreach ($configs as $config) {
            if (!empty($config)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get File Loader
     *
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function getFileLoader($container)
    {
        return new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    }
}
