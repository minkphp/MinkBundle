<?php

/*
 * This file is part of the Behat MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Behat\MinkBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Behat\Mink\Mink;

/**
 * Mink PHPUnit test case.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 * @author Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class MinkTestCase extends WebTestCase
{
    /**
     * Full URL to local phpunit_coverage.php
     *
     * http://local-host/bundles/mink/phpunit_coverage.php
     *
     * @var string
     */
    protected $coverageScriptUrl;

    /**
     * @var boolean
     */
    private $collectCodeCoverageInformation;

    /**
     * @var string
     */
    private $testId;

    /**
     * @var \Behat\Mink\Mink
     */
    private static $mink;

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (null !== self::$mink) {
            self::$mink->stopSessions();
            self::$mink = null;
        }
    }

    /**
     * @param Mink $mink
     */
    public function setMink(Mink $mink = null)
    {
        self::$mink = $mink;
    }

    /**
     * @return \Behat\Mink\Mink
     */
    public function getMink()
    {
        if (null == self::$mink) {
            $container = $this->getKernel()->getContainer();
            $this->coverageScriptUrl = $container->getParameter('mink.coverage_script_url');
            self::$mink = $container->get('behat.mink');
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
    public function getContainer()
    {
        return $this->getKernel()->getContainer();
    }

    protected function initTestCoverage()
    {
        $this->testId = get_class($this).'__'.$this->getName();
        if ($this->collectCodeCoverageInformation && $this->coverageScriptUrl) {
            $this->getMink()->getSession()->setCookie('PHPUNIT_SELENIUM_TEST_ID', $this->testId);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function runTest()
    {
        $this->initTestCoverage();

        return parent::runTest();
    }

    /**
     * @param  string $path
     * @return string
     * @author Mattis Stordalen Flister <mattis@xait.no>
     */
    protected function findDirectorySeparator($path)
    {
        if (strpos($path, '/') !== false) {
            return '/';
        }

        return '\\';
    }

    /**
     * @param array $coverage
     *
     * @return array
     *
     * @author Mattis Stordalen Flister <mattis@xait.no>
     */
    protected function matchLocalAndRemotePaths(array $coverage)
    {
        $coverageWithLocalPaths = array();

        foreach ($coverage as $originalRemotePath => $data) {
            $remotePath = $originalRemotePath;
            $separator  = $this->findDirectorySeparator($remotePath);

            while (!($localpath = stream_resolve_include_path($remotePath)) &&
                strpos($remotePath, $separator) !== false) {
                $remotePath = substr($remotePath, strpos($remotePath, $separator) + 1);
            }

            if ($localpath && md5_file($localpath) == $data['md5']) {
                $coverageWithLocalPaths[$localpath] = $data['coverage'];
            }
        }

        return $coverageWithLocalPaths;
    }

    /**
     * @param \PHPUnit_Framework_TestResult $result
     *
     * @return \PHPUnit_Framework_TestResult
     */
    public function run(\PHPUnit_Framework_TestResult $result = null)
    {
        if (null === $result) {
            $result = $this->createResult();
        }

        $this->collectCodeCoverageInformation = $result->getCollectCodeCoverageInformation();

        parent::run($result);

        if (($mink = $this->getMink()) &&
            'symfony' != $mink->getDefaultSessionName() &&
            $this->collectCodeCoverageInformation &&
            $this->coverageScriptUrl
        ) {
            $session = $mink->getSession('goutte');

            $url = sprintf(
                '%s?PHPUNIT_SELENIUM_TEST_ID=%s',
                $this->coverageScriptUrl,
                $this->testId
            );

            $session->visit($url);

            $coverage = array();
            if ($content = $session->getPage()->getContent()) {
                $coverage = unserialize($content);
                if (is_array($coverage)) {
                    $coverage = $this->matchLocalAndRemotePaths($coverage);
                } else {
                    throw new \RuntimeException('Empty or invalid code coverage data received from url "'.$url.'"');
                }
            }

            $result->getCodeCoverage()->append(
                $coverage,
                $this
            );
        }

        return $result;
    }
}
