<?php

namespace Behat\MinkBundle\Driver;

use Symfony\Component\BrowserKit\Client;

use Behat\Mink\Driver\GoutteDriver;

/*
 * This file is part of the Behat\MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Symfony2 Mink driver.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class SymfonyDriver extends GoutteDriver
{
    /**
     * Initializes Goutte driver.
     *
     * @param   Symfony\Component\BrowserKit\Client $client     BrowserKit client instance
     */
    public function __construct(Client $client = null)
    {
        // create new kernel, that could be easily rebooted
        $class  = get_class($client->getKernel());
        $kernel = new $class($client->getKernel()->getEnvironment(), $client->getKernel()->isDebug());
        $kernel->boot();

        parent::__construct($kernel->getContainer()->get('test.client'));
    }

    /**
     * {@inheritdoc}
     *
     * removes "*.php/" from urls and then passes it to GoutteDriver::visit().
     */
    public function visit($url)
    {
        $url = preg_replace('/^https?\:\/\/[^\/]+(.+\.php)?/', '', $url);
        parent::visit($url);
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        parent::reset();

        $this->getClient()->getKernel()->shutdown();
        $this->getClient()->getKernel()->boot();
    }

    /**
     * {@inheritdoc}
     */
    public function setBasicAuth($user, $password)
    {
        $this->getClient()->setServerParameter('PHP_AUTH_USER', $user);
        $this->getClient()->setServerParameter('PHP_AUTH_PW', $password);
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestHeader($name, $value)
    {
        switch (strtolower($name)) {
            case 'accept':
                $name = 'HTTP_ACCEPT';
                break;
            case 'accept-charset':
                $name = 'HTTP_ACCEPT_CHARSET';
                break;
            case 'accept-encoding':
                $name = 'HTTP_ACCEPT_ENCODING';
                break;
            case 'accept-language':
                $name = 'HTTP_ACCEPT_LANGUAGE';
                break;
            case 'connection':
                $name = 'HTTP_CONNECTION';
                break;
            case 'host':
                $name = 'HTTP_HOST';
                break;
            case 'user-agent':
                $name = 'HTTP_USER_AGENT';
                break;
            case 'authorization':
                $name = 'PHP_AUTH_DIGEST';
                break;
        }

        $this->getClient()->setServerParameter($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->getClient()->getResponse()->getStatusCode();
    }
}
