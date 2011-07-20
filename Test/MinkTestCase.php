<?php

namespace Behat\MinkBundle\Test;

use Symfony\Component\Finder\Finder,
    Symfony\Component\HttpKernel\HttpKernelInterface;

use Behat\Mink\Mink,
    Behat\Mink\PHPUnit\TestCase as BaseMinkTestCase;

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
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
abstract class MinkTestCase extends BaseMinkTestCase
{
    private static $kernelClass;
    private static $kernel;
    private static $mink;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        static::$kernel = static::createKernel();

        if (null === static::$mink) {
            static::$mink = static::$kernel->getContainer()->get('behat.mink');
        }
    }

    /**
     * Returns kernel instance.
     *
     * @return  HttpKernelInterface A HttpKernelInterface instance
     */
    public function getKernel()
    {
        return static::$kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function getMink()
    {
        return static::$mink;
    }

    /**
     * {@inheritdoc}
     */
    protected function registerSessions(Mink $mink)
    {
    }

    /**
     * Creates a Kernel.
     *
     * @param   string      $environment    environment name (test, prod, debug)
     * @param   Boolean     $debug          debug mode
     *
     * @return  HttpKernelInterface A HttpKernelInterface instance
     */
    protected static function createKernel($environment = 'test', $debug = true)
    {
        if (null === static::$kernelClass) {
            static::$kernelClass = static::getKernelClass();
        }

        $kernel = new static::$kernelClass($environment, $debug);
        $kernel->boot();

        return $kernel;
    }

    /**
     * Attempts to guess the kernel location.
     *
     * When the Kernel is located, the file is required.
     *
     * @return string The Kernel class name
     */
    protected static function getKernelClass()
    {
        $dir = isset($_SERVER['KERNEL_DIR']) ? $_SERVER['KERNEL_DIR'] : static::getPhpUnitXmlDir();

        $finder = new Finder();
        $finder->name('*Kernel.php')->depth(0)->in($dir);
        if (!count($finder)) {
            throw new \RuntimeException('You must override the WebTestCase::createKernel() method.');
        }

        $file = current(iterator_to_array($finder));
        $class = $file->getBasename('.php');

        require_once $file;

        return $class;
    }

    /**
     * Finds the directory where the phpunit.xml(.dist) is stored.
     *
     * If you run tests with the PHPUnit CLI tool, everything will work as expected.
     * If not, override this method in your test classes.
     *
     * @return string The directory where phpunit.xml(.dist) is stored
     */
    private static function getPhpUnitXmlDir()
    {
        if (!isset($_SERVER['argv']) || false === strpos($_SERVER['argv'][0], 'phpunit')) {
            throw new \RuntimeException('You must override the WebTestCase::createKernel() method.');
        }

        $dir = static::getPhpUnitCliConfigArgument();
        if ($dir === null &&
            (file_exists(getcwd().DIRECTORY_SEPARATOR.'phpunit.xml') ||
            file_exists(getcwd().DIRECTORY_SEPARATOR.'phpunit.xml.dist'))) {
            $dir = getcwd();
        }

        // Can't continue
        if ($dir === null) {
            throw new \RuntimeException('Unable to guess the Kernel directory.');
        }

        if (!is_dir($dir)) {
            $dir = dirname($dir);
        }

        return $dir;
    }

    /**
     * Finds the value of configuration flag from cli
     *
     * PHPUnit will use the last configuration argument on the command line, so this only returns
     * the last configuration argument
     *
     * @return string The value of the phpunit cli configuration option
     */
    private static function getPhpUnitCliConfigArgument()
    {
        $dir = null;
        $reversedArgs = array_reverse($_SERVER['argv']);
        foreach ($reversedArgs as $argIndex=>$testArg) {
            if ($testArg === '-c' || $testArg === '--configuration') {
                $dir = realpath($reversedArgs[$argIndex - 1]);
                break;
            } elseif (strpos($testArg, '--configuration=') === 0) {
                $argPath = substr($testArg, strlen('--configuration='));
                $dir = realpath($argPath);
                break;
            }
        }

        return $dir;
    }
}
