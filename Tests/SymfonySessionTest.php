<?php

namespace Behat\MinkBundle\Tests;

class SymfonySessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'symfony';
    }
}
