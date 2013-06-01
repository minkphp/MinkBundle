<?php

namespace Behat\MinkBundle\Test;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Behat\Mink\Mink;

/*
 * This file is part of the Behat\MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Mink PHPUnit test case.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 * @author Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class MinkTestCase extends WebTestCase
{
    /**
     * @var /Behat/Mink/Mink
     */
    private static $mink;

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (null !== self::$mink) {
            self::$mink->stopSessions();
        }
    }

    /**
     * @return \Behat\Mink\Mink
     */
    public function getMink()
    {
        if (null === self::$mink) {
            self::$mink = static::getKernel()->getContainer()->get('behat.mink');
        }

        return self::$mink;
    }

    /**
     * @return \Symfony\Component\HttpKernel\Kernel
     */
    public static function getKernel()
    {
        if (null === static::$kernel) {
            static::$kernel = static::createKernel();
        }
        if (!static::$kernel->getContainer()) {
            static::$kernel->boot();
        }
        return static::$kernel;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    public static function getContainer()
    {
        return static::getKernel()->getContainer();
    }
}
