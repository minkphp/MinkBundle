<?php
namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

class SeleniumSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'selenium';
    }
}
