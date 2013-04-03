<?php

namespace Behat\MinkBundle\Tests;

class Selenium2SessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'selenium2';
    }
}
