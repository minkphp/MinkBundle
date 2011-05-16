<?php

namespace Behat\MinkBundle\Tests;

class GoutteSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'goutte';
    }
}
