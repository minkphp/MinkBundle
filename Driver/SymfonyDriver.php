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
        parent::__construct($client);
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
    public function setRequestHeader($name, $value)
    {
        switch ($name) {
            case 'Accept':
                $name = 'HTTP_ACCEPT';
                break;
            case 'Accept-Charset':
                $name = 'HTTP_ACCEPT_CHARSET';
                break;
            case 'Accept-Encoding':
                $name = 'HTTP_ACCEPT_ENCODING';
                break;
            case 'Accept-Language':
                $name = 'HTTP_ACCEPT_LANGUAGE';
                break;
            case 'Connection':
                $name = 'HTTP_CONNECTION';
                break;
            case 'Host':
                $name = 'HTTP_HOST';
                break;
            case 'User-Agent':
                $name = 'HTTP_USER_AGENT';
                break;
            case 'Authorization':
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
