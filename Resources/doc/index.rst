MinkBundle for Symfony2
=======================

MinkBundle is Symfony2 bundle (plugin) created in order to give ability to
write functional tests for Symfony2 applications.

Mink Installation
-----------------

In order to be able to use MinkBundle, you need to install Mink first.

Method #1 (PEAR)
~~~~~~~~~~~~~~~~

The simplest way to install Mink is through PEAR:

.. code-block:: bash

    $ pear channel-discover pear.behat.org
    $ pear install behat/mink

Now you should include Mink's autoloader into your ``app/autoload.php``:

.. code-block:: php

    <?php
    
    //...
    
    // remove Symfony2 classes from Mink autoload
    // routine, cuz Symfony2 already autoloads them
    // by itself
    define('BEHAT_AUTOLOAD_ZF2', false);

    // require autoloader
    require_once 'mink/autoload.php';

Method #2 (Git)
~~~~~~~~~~~~~~~

Add next lines to your ``deps`` file:

.. code-block:: ini

    [mink]
        git=https://github.com/Behat/Mink.git
        target=/behat/mink

By default, ``MinkBundle`` will use custom ``SymfonyDriver``. But if you want
to use ``GoutteDriver``, you'll need to also add:

.. code-block:: ini

    [goutte]
        git=https://github.com/fabpot/Goutte.git
        target=/goutte
    [zend]
        git=https://github.com/zendframework/zf2.git

And if you want to use ``SahiDriver``, you'll need to add another 2 lines:

.. code-block:: ini

    [buzz]
        git=https://github.com/kriswallsmith/Buzz.git
        target=/buzz
    [SahiClient]
        git=https://github.com/Behat/SahiClient
        target=/behat/sahi

Now run:

.. code-block:: bash

    bin/vendors install

in order to install all missing parts.

It's time to setup your ``app/autoload.php``:

.. code-block:: php

    $loader->registerNamespaces(array(
    //...
        'Behat\Mink'       => __DIR__.'/../vendor/behat/mink/src',

        // if you want to use GoutteDriver
        'Goutte'           => __DIR__.'/../vendor/goutte/src',
        'Zend'             => __DIR__.'/../vendor/zend/library',

        // if you want to use SahiDriver
        'Behat\SahiClient' => __DIR__.'/../vendor/behat/sahi/src',
        'Buzz'             => __DIR__.'/../vendor/buzz/lib',
    //...
    ));

Bundle Installation & Setup
---------------------------

Now it's time to install and setup ``MinkBundle`` itself.

1. Add ``MinkBundle`` repository address to your ``deps`` file:

    .. code-block:: ini

        [MinkBundle]
            git=https://github.com/Behat/MinkBundle.git
            target=/bundles/Behat/MinkBundle

2. Add  it to ``app/autoload.php``:

    .. code-block:: php

        $loader->registerNamespaces(array(
        //...
            'Behat\MinkBundle' => __DIR__.'/../vendor/bundles',
        //...
        ));

3. And to ``app/AppKernel.php``:

    .. code-block:: php

        if ('test' === $this->getEnvironment()) {
            $bundles[] = new Behat\MinkBundle\MinkBundle();
        }

4. Run ``bin/vendors install``

Bundle Configuration
~~~~~~~~~~~~~~~~~~~~

Now, as you've setted up the bundle, you should configure it:

.. code-block:: yaml

    # app/config/config_test.yml
    mink:
        base_url:   http://your_app.dev/app_test.php

By default, MinkBundle uses only ``SymfonyDriver`` session. If you want to use
``GoutteDriver`` or ``SahiDriver`` sessions - you should specify them in config:

.. code-block:: yaml

    # app/config/config_test.yml
    mink:
        base_url:   http://your_app.dev/app_test.php
        goutte:     ~
        sahi:       ~

Writing your first test
-----------------------

Now, as you've configured ``MinkBundle``, you can use the special ``MinkTestCase``,
provided with it as a base class for your tests:

.. code-block:: php

    <?php

    namespace Acme\AcmeBundle\Tests;

    use Behat\MinkBundle\Test\MinkTestCase;

    class AcmeWebTestCase extends MinkTestCase
    {
        protected $base;

        protected function setUp()
        {
            parent::setUp();

            $this->base = $this->getKernel()
                ->getContainer()
                ->getParameter('behat.mink.start_url');
        }

        // write functional tests
    }

Base ``Behat\MinkBundle\Test\MinkTestCase`` class provides an easy way to get
``$mink`` and specific session instances in your tests:

1. ``symfony`` session will be used by default, so ``getSession()`` without
   parameters will return ``test.client`` enabled session for you:

   .. code-block:: php

     $session = $this->getSession();
     // or you can use the more verbose call:
     $session = $this->getSession('symfony');

2. If you want to test your application with **real** HTTP requests, you should
   use ``goutte`` session:

   .. code-block:: php

     $session = $this->getSession('goutte');

3. Or if you want to test your app running in real browser - use ``sahi``
   session:

   .. code-block:: php

     $session = $this->getSession('sahi');

After you've choosen needed session - use it to perform actions on your
Symfony2 app:

.. code-block:: php

    $session
        ->visit($this->base.'_behat/tests/page/page1');
    $this->assertTrue(
        $session->getPage()->hasContent('Page N1')
    );

    $session->getPage()->clickLink('p10');

For example, spec form test with ``symfony`` session will look like that:

.. code-block:: php

    public function testForms()
    {
        $session = $this->getSession();

        $session->visit($this->base . '_behat/tests/form');
        $page = $session->getPage();

        // 3. FILL FORMS:

        $page->fillField('name', 'ever');
        $page->fillField('age', '23');
        $page->selectFieldOption('speciality', 'programmer');
        $page->clickButton('Send spec info');

        // 4. ASSERT RESPONSE:

        $this->assertTrue(
            $page->hasContent('POST recieved')
        );
        $this->assertTrue(
            $page->hasContent('ever is 23 years old programmer')
        );
    }
