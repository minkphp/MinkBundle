<?php
namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

class ZombieSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'zombie';
    }
}
