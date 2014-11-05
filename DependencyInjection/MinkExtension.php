<?php

/*
 * This file is part of the Behat MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Behat\MinkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * Symfony2 Behat\Mink extension.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class MinkExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor      = new Processor();
        $configuration  = new Configuration();
        $config         = $processor->processConfiguration($configuration, $configs);

        $loader = $this->getFileLoader($container);
        $loader->load('mink.xml');
        if (isset($config['goutte'])) {
            if (false == class_exists('Behat\Mink\Driver\GoutteDriver')) {
                throw new \RuntimeException(
                    'Cannot find "GoutteDriver". Have you installed behat/mink-goutte-driver package?'
                );
            }
            $loader->load('sessions/goutte.xml');
        }
        if (isset($config['sahi'])) {
            if (false == class_exists('Behat\Mink\Driver\SahiDriver')) {
                throw new \RuntimeException(
                    'Cannot find "SahiDriver". Have you installed behat/mink-sahi-driver package?'
                );
            }
            $loader->load('sessions/sahi.xml');
        }
        if (isset($config['zombie'])) {
            if (false == class_exists('Behat\Mink\Driver\ZombieDriver')) {
                throw new \RuntimeException(
                    'Cannot find "ZombieDriver". Have you installed behat/mink-zombie-driver package?'
                );
            }
            $loader->load('sessions/zombie.xml');
        }
        if (isset($config['selenium'])) {
            if (false == class_exists('Behat\Mink\Driver\SeleniumDriver')) {
                throw new \RuntimeException(
                    'Cannot find "SeleniumDriver". Have you installed behat/mink-selenium-driver package?'
                );
            }
            $loader->load('sessions/selenium.xml');
        }
        if (isset($config['selenium2'])) {
            if (false == class_exists('Behat\Mink\Driver\Selenium2Driver')) {
                throw new \RuntimeException(
                    'Cannot find "Selenium2Driver". Have you installed behat/mink-selenium-driver package?'
                );
            }
            $loader->load('sessions/selenium2.xml');
        }

        foreach ($config as $ns => $tlValue) {
            if (!is_array($tlValue)) {
                $container->setParameter("mink.$ns", $tlValue);
            } else {
                foreach ($tlValue as $name => $value) {
                    $container->setParameter("mink.$ns.$name", $value);
                }
            }
        }

        if (!$container->hasParameter('mink.paths.lib')) {
            $minkReflection = new \ReflectionClass('Behat\Mink\Mink');
            $minkLibPath    = realpath(dirname($minkReflection->getFilename()).'/../../../');
            $container->setParameter('mink.paths.lib', $minkLibPath);
        }
    }

    /**
     * Get File Loader
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function getFileLoader($container)
    {
        return new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    }
}
