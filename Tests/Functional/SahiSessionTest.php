<?php

namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

/**
 * @group sahi
 */
class SahiSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        $this->setMink();

        return 'sahi';
    }
}
