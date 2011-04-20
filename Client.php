<?php

namespace Behat\MinkBundle;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;

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
}
