<?php
namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

class ZombieSessionTest extends SahiSessionTest
{
    protected function getSessionName()
    {
        $this->markTestIncomplete('To be done');
        return 'zombie';
    }
}
