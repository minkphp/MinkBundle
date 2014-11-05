<?php

namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

/**
 * @group selenium
 */
class SeleniumSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'selenium';
    }
}
