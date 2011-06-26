<?php

namespace Behat\MinkBundle;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient,
    Symfony\Component\BrowserKit\Request;

/*
 * This file is part of the Behat\MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Extension to default test.client, which doesn't reboot kernel.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Client extends BaseClient
{
    /**
     * {@inheritdoc}
     *
     * Makes a request without kernel shutdown.
     */
    protected function doRequest($request)
    {
        return $this->getKernel()->handle($request);
    }

    /**
     * {@inheritdoc}
     *
     * Sets server variables from request headers.
     */
    protected function filterRequest(Request $request)
    {
        foreach ($request->getServer() as $name => $value) {
            $_SERVER[$name] = $value;
        }

        return parent::filterRequest($request);;
    }
}
