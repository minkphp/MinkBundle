<?php

namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

/**
 * @group selenium2
 */
class Selenium2SessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'selenium2';
    }
}
