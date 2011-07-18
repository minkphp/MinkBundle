Provides Behat\Mink browser abstraction library for your Symfony2 project.
See [Behat official site](http://behat.org) for more info.

## Features

- Clean, unified and well-documented API
- Support for Symfony2 test.client browser emulator
- Support for Goutte browser emulator
- Support for Sahi (JS testing) browser emulator

## Installation

### Add Behat\Mink and Behat\MinkBundle to your vendors dir

``` bash
git submodule add -f git://github.com/Behat/Mink.git vendor/Behat/Mink
git submodule add -f git://github.com/Behat/MinkBundle.git vendor/Behat/MinkBundle
```

### Add Behat\Mink and Behat\MinkBundle namespaces to autoload.php

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

### Enable BehatMinkBundle and test.client in your configuration:

``` yml
# app/config/config_dev.yml
framework:
    test:  ~

# ...

behat_mink:
    base_url:  http://your_app_local.url/app_dev.php/
```

By default, Mink will use Symfony2's test.client service to test your application, but you can enable and
use other sessions when running the test suite.

Notice, that `base_url` is a required parameter for any Mink session. The best practice is to create a new
frontend controller for testing needs:

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

Change the `base_url` parameter to use this frontend controller in your test suits (including Goutte and Sahi sessions):

``` yml
# ...

behat_mink:
    base_url:  http://your_app_local.url/app_test.php/
```

## GoutteSession

By default, all Mink actions will be run against Symfony2's test.client service, which **will not** reboot
the kernel between requests. But what if you want to isolate your test suite from real application and,
more importantly, isolate different requests and responses from each other? Then you'll need to use Goutte session.

### Add Goutte and Zend libraries to your vendors dir

``` bash
git submodule add -f git://github.com/fabpot/Goutte.git vendor/Goutte
git submodule add -f git://github.com/zendframework/zf2.git vendor/Zend
```

### Add Goutte and Zend namespaces to autoload.php

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

### Enable Mink GoutteSession in your project configuration file

``` yml
# ...

behat_mink:
    base_url:  http://your_app_local.url/app_test.php/
    goutte:    ~
```

## SahiSession

Sometimes, headless browser emulators are not enough to test your complex application, especially when
you want to test AJAX or JS functionality. Sahi session comes to the rescue!

### Add Behat\SahiClient and Buzz libraries to your vendors dir

``` bash
git submodule add -f git://github.com/Behat/SahiClient.git vendor/Behat/SahiClient
git submodule add -f git://github.com/kriswallsmith/Buzz.git vendor/Buzz
```

### Add Behat\SahiClient and Buzz namespaces to autoload.php

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

### Enable Mink SahiSession in your project configuration file

``` yml
# ...

behat_mink:
    base_url: http://your_app_local.url/app_test.php/
    goutte:   ~   # enable both Goutte
    sahi:     ~   # and Sahi session
```

## Writing your first test

Writing your first Mink test case is simple! Use MinkBundle's `MinkTestCase` class:

``` php
<?php

namespace Acme\AcmeBundle\Tests;

use Behat\MinkBundle\Test\MinkTestCase;

class AcmeWebTestCase extends MinkTestCase
{
    protected $base;

    protected function setUp()
    {
        $this->base = static::$kernel->getContainer()->getParameter('behat.mink.start_url');
    }

    public function testSimpleBrowsing()
    {
        /* 1. CHOOSE SESSION:

        Symfony2 test.client session will be used by default, so
        getSession() without parameters will return test.client
        session for you in any place of your app.

            $session = static::$mink->getSession();

        OR with more verbose version:

            $session = static::$mink->getSession('symfony');

        Also, you can use headless goutte session, which will
        make real HTTP requests against your Symfony2 application:

            $session = static::$mink->getSession('goutte');

        OR even to JS-enabled in-browser Sahi session, which will
        start real browser and make real requests through it:

            $session = static::$mink->getSession('sahi');

        */

        // 2. DO ACTIONS (all actions below works similar for ALL Mink sessions):

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
    }

    public function testForms()
    {
        $session = static::$mink->getSession();

        $session->visit($this->base . '_behat/tests/form');
        $page = $session->getPage();

        // 3. FILL FORMS:

        $page->fillField('name', 'ever');
        $page->fillField('age', '23');
        $page->selectFieldOption('speciality', 'programmer');
        $page->clickButton('Send spec info');

        // 4. ASSERT RESPONSE:

        $this->assertTrue($page->hasContent('POST recieved'));
        $this->assertTrue($page->hasContent('ever is 23 years old programmer'));
    }
}
```

For further learning, read the [http://docs.behat.org/api/mink/index.html](Mink API docs).

## CREDITS

List of developers who have contributed:

- Konstantin Kudryashov (ever.zet@gmail.com)
