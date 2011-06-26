<?php

namespace Behat\MinkBundle\Tests;

class SymfonySessionTest extends BaseSessionTestCase
{
    protected function getSessionName()
    {
        return 'symfony';
    }

    public function testHeaders()
    {
        $session = static::$mink->getSession();

        $session->setRequestHeader('Accept-Language', 'fr');
        $session->visit($this->base . '_behat/tests/headers');
        $this->assertContains("'HTTP_ACCEPT_LANGUAGE' => 'fr'", $session->getPage()->getContent());
    }
}
