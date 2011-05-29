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
        parent::__construct('/', $client);
    }

    /**
     * {@inheritdoc}
     *
     * removes "*.php/" from urls and the passes it to GoutteDriver::visit().
     */
    public function visit($url)
    {
        $url = preg_replace('/[^\/]+\.php\//', '', $url);
        parent::visit($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->getClient()->getResponse()->getStatusCode();
    }
}
