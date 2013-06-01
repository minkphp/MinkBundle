<?php
namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

class Selenium2SessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'selenium2';
    }
}
