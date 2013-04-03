<?php

namespace Behat\MinkBundle\Tests;

class SeleniumSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'selenium';
    }
}
