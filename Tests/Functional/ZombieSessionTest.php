<?php

namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

/**
 * @group zombie
 */
class ZombieSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        $this->setMink();

        return 'zombie';
    }
}
