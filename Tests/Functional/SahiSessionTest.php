<?php
namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

class SahiSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'sahi';
    }
}
