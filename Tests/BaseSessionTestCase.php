<?php

namespace Behat\MinkBundle\Tests;

use Behat\MinkBundle\Test\MinkTestCase;

abstract class BaseSessionTestCase extends MinkTestCase
{
    protected $base;

    abstract protected function getSessionName();

    protected function setUp()
    {
        $this->getMink()->setDefaultSessionName(static::getSessionName());
        $this->base = static::getContainer()->getParameter('behat.mink.base_url');
    }

    public function testSimpleBrowsing()
    {
        $session = $this->getMink()->getSession();

        $session->visit($this->base . '/_behat/tests/page/page1');
        $this->assertTrue($session->getPage()->hasContent('Page N1'));
        $this->assertFalse($session->getPage()->hasContent('Page N2'));

        $session->visit($this->base . '/_behat/tests/page/page2');
        $this->assertTrue($session->getPage()->hasContent('Page N2'));
        $this->assertFalse($session->getPage()->hasContent('Page N1'));

        $session->visit($this->base . '/_behat/tests/page/page1');
        $session->getPage()->clickLink('p10');
        $this->assertTrue($session->getPage()->hasContent('Page N10'));

        try {
            $session->getPage()->clickLink('p100');
            $this->fail('Unexisting link should throw exception onClick');
        } catch (\Behat\Mink\Exception\ElementNotFoundException $e) {}
    }

    public function testRedirects()
    {
        $session = $this->getMink()->getSession();

        $session->visit($this->base . '/_behat/tests/redirect');
        $this->assertTrue($session->getPage()->hasContent('Page N1'));
    }

    public function testForms()
    {
        $session = $this->getMink()->getSession();

        $session->visit($this->base . '/_behat/tests/form');
        $page = $session->getPage();

        $page->fillField('name', 'ever');
        $page->fillField('age', '23');
        $page->selectFieldOption('speciality', 'manager');
        $page->pressButton('Send spec info');

        $this->assertTrue($page->hasContent('POST recieved'));
        $this->assertTrue($page->hasContent('ever is 23 years old manager'));
    }
}
