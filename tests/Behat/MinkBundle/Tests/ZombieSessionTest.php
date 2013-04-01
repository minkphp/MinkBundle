<?php

namespace Behat\MinkBundle\Tests;

class ZombieSessionTest extends SahiSessionTest
{
    protected function getSessionName()
    {
        return 'zombie';
    }
}
