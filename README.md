Provides Behat\Mink browser abstraction library for your Symfony2 project.
See [Behat official site](http://behat.org) for more info.

## Features

- Clean, unified and well-documented API
- Support for Symfony2 test.client browser emulator
- Support for Goutte browser emulator
- Support for Sahi (JS testing) browser emulator

## Installation

### Add Behat\Mink and Behat\MinkBundle into your vendors dir.

``` bash
git submodule add -f git://github.com/Behat/Mink.git vendor/Behat/Mink
git submodule add -f git://github.com/Behat/MinkBundle.git vendor/Behat/MinkBundle
```

### Add Behat\Mink and Behat\MinkBundle namespaces to autoload

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Behat\Mink'       => __DIR__.'/../vendor/Behat/Mink/src',
    'Behat\MinkBundle' => __DIR__.'/../vendor',
    // ...
));
```

### Add Behat\MinkBundle into your application kernel (for `test` and `dev` environments)

``` php
<?php
// app/AppKernel.php
if (in_array($this->getEnvironment(), array('dev', 'test'))) {
    // ...
    $bundles[] = new Behat\MinkBundle\BehatMinkBundle();
    // ...
}
```

### Enable MinkBundle and test.client in your configuration:

``` yml
# app/config/config_dev.yml
framework:
    test:       ~

# ...

behat_mink:
    start_url:  http://your_app_local.url/app_dev.php/
```

By default, Mink will use Symfony2 test.client to test your application, but you can enable and
switch to other drivers anytime during testsuite run.

Notice, that `start_url` is required parameter for any Mink driver. Best practice is to create new
front controller for testing needs:

``` php
<?php
// web/app_test.php

if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
    header('HTTP/1.0 403 Forbidden');
    die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel('test', true);
$kernel->handle(Request::createFromGlobals())->send();
```

and use it in your test suites (front controller will be used by Goutte and Sahi drivers):

``` yml
# ...

behat_mink:
    start_url:  http://your_app_local.url/app_test.php/
```

## GoutteDriver

By default all Mink actions will be runed against Symfony2 test.client, which **will not** reboot
kernel between requests. But what if you want to isolate your test suite from real application and,
what's even more important, to isolate different requests and responses from each others. Then you
need to use Goutte driver.

### Add Goutte and Zend libraries to your vendors dir.

``` bash
git submodule add -f git://github.com/fabpot/Goutte.git vendor/Goutte
git submodule add -f git://github.com/zendframework/zf2.git vendor/Zend
```

### Add Goutte and Zend namespaces to autoload

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Goutte'           => __DIR__.'/../vendor/Goutte/src',
    'Zend'             => __DIR__.'/../vendor/Zend/library',
    // ...
));
```

### Enable Mink GoutteDriver in your project configuration file

``` yml
# ...

behat_mink:
    start_url:  http://your_app_local.url/app_test.php/
    goutte:     ~
```

## SahiDriver

Sometimes, headless browser emulators are not enough to test your complex applications. Especially
in case when you want to test AJAX or JS functionality. Sahi driver comes to rescue! So, you need to
enable it...

### Add Behat\SahiClient and Buzz libraries to your vendors dir.

``` bash
git submodule add -f git://github.com/Behat/SahiClient.git vendor/Behat/SahiClient
git submodule add -f git://github.com/kriswallsmith/Buzz.git vendor/Buzz
```

### Add Behat\SahiClient and Buzz namespaces to autoload

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Behat\SahiClient' => __DIR__.'/../vendor/Behat/SahiClient/src',
    'Buzz'             => __DIR__.'/../vendor/Buzz/lib',
    // ...
));
```

### Enable Mink SahiDriver in your project configuration file

``` yml
# ...

behat_mink:
    start_url:  http://your_app_local.url/app_test.php/
    goutte:     ~   # enable both Goutte
    sahi:       ~   # and Sahi drivers
```

## Writing first test

How to write your first Mink test case? Simple! Use bundled with MinkBundle `MinkTestCase`:

``` php
<?php

namespace Acme\AcmeBundle\Tests;

use Behat\MinkBundle\Test\MinkTestCase;

class AcmeWebTestCase extends MinkTestCase
{
    protected $base;

    abstract protected function getDriverName();

    protected function setUp()
    {
        $this->base = static::$kernel->getContainer()->getParameter('behat.mink.start_url');
    }

    public function testSimpleBrowsing()
    {
        // choose driver:
        // Symfony2 test.client driver will be used by default:
        // you don't need to do anything
        // OR you can switch to headless goutte driver:
        // static::$mink->switchToDriver('goutte');
        // OR to JS-enabled in-browser Sahi driver:
        // static::$mink->swithcToDriver('sahi');

        // all actions below works similar for ALL Mink drivers:

        $session = static::$mink->getSession();

        $session->visit($this->base . '_behat/tests/page/page1');
        $this->assertTrue($session->getPage()->hasContent('Page N1'));
        $this->assertFalse($session->getPage()->hasContent('Page N2'));

        $session->visit($this->base . '_behat/tests/page/page2');
        $this->assertTrue($session->getPage()->hasContent('Page N2'));
        $this->assertFalse($session->getPage()->hasContent('Page N1'));

        $session->visit($this->base . '_behat/tests/page/page1');
        $session->getPage()->clickLink('p10');
        $this->assertTrue($session->getPage()->hasContent('Page N10'));

        try {
            $session->getPage()->clickLink('p100');
            $this->fail('Unexisting link should throw exception onClick');
        } catch (\Behat\Mink\Exception\ElementNotFoundException $e) {}

        // after each test case, Mink will automatically switch to default driver
    }

    public function testForms()
    {
        $session = static::$mink->getSession();

        $session->visit($this->base . '_behat/tests/form');
        $page = $session->getPage();

        $page->fillField('name', 'ever');
        $page->fillField('age', '23');
        $page->selectFieldOption('speciality', 'programmer');
        $page->clickButton('Send spec info');

        $this->assertTrue($page->hasContent('POST recieved'));
        $this->assertTrue($page->hasContent('ever is 23 years old programmer'));
    }
}
```

[http://docs.behat.org/api/mink/index.html](Mink API docs)

## CREDITS

List of developers who contributed:

- Konstantin Kudryashov (ever.zet@gmail.com)
