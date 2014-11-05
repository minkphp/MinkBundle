<?php

namespace Behat\MinkBundle\Tests\Functional;

use Behat\MinkBundle\Tests\BaseSessionTestCase;

/**
 * @group goutte
 */
class GoutteSessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'goutte';
    }

    public function testHeaders()
    {
        $session = $this->getMink()->getSession();

        $session->setRequestHeader('Accept-Language', 'fr');
        $session->visit($this->base.'/_behat/tests/headers');
        $this->assertContains("'accept-language' =>", $session->getPage()->getContent());
        $this->assertContains("'fr'", $session->getPage()->getContent());

        $session->setRequestHeader('Accept-Language', 'ru');
        $session->visit($this->base.'/_behat/tests/headers');
        $this->assertContains("'accept-language' =>", $session->getPage()->getContent());
        $this->assertContains("'ru'", $session->getPage()->getContent());
    }
}
