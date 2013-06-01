MinkBundle for Symfony2
=======================

MinkBundle is Symfony2 bundle (plugin) created in order to give ability to
write functional tests for Symfony2 applications.

Mink Installation
-----------------

Composer
~~~~~~~~~~~~~~~

Add next lines to your ``composer.json`` file:

.. code-block:: json

    "require-dev": {
        ...
        "behat/mink-bundle": "dev-master"
    }

By default, ``MinkBundle`` will use custom ``SymfonyDriver``. But if you want
to use ``GoutteDriver``, you'll need to also add:

.. code-block:: json

    "require-dev": {
        ...
        "behat/mink-bundle": "dev-master",
        "behat/mink-goutte-driver": "1.0.*"
    }

And if you want to use ``SahiDriver``, you'll need to add another line:

.. code-block:: json

    "require-dev": {
        ...
        "behat/mink-bundle": "dev-master",
        "behat/mink-sahi-driver": "1.0.*"
    }

Now run:

.. code-block:: bash

    composer.phar install --dev

in order to install all parts.


Bundle Installation & Setup
---------------------------

Now it's time to install and setup ``MinkBundle`` itself.

And to ``app/AppKernel.php``:

    .. code-block:: php

        if ('test' === $this->getEnvironment()) {
            $bundles[] = new Behat\MinkBundle\MinkBundle();
        }

Bundle Configuration
~~~~~~~~~~~~~~~~~~~~

Now, as you've setted up the bundle, you should configure it:

.. code-block:: yaml

    # app/config/config_test.yml
    mink:
        base_url:   http://your_app.dev/app_test.php

By default, MinkBundle uses only ``SymfonyDriver`` session. If you want to use
``GoutteDriver``, ``SahiDriver`` or ``ZombieDriver`` sessions - you should
specify them in config explicitly:

.. code-block:: yaml

    # app/config/config_test.yml
    mink:
        base_url:   http://your_app.dev/app_test.php
        goutte:     ~
        sahi:       ~
        zombie:     ~

Out of the box, Mink will use ``SymfonyDriver`` session as default one. This
means that any session call without argument:

.. code-block:: php

    $this->getMink()->getSession()->...;

will be done against default Symfony2 ``test.client`` library. If you want to
change this, use ``default_session`` configuration option:

.. code-block:: yaml

    # app/config/config_test.yml
    mink:
        base_url:           http://your_app.dev/app_test.php
        default_session:    goutte
        goutte:             ~

.. note::

    Note, that we do our configuration in ``config_test.yml``. It's convenient
    way to configure MinkBundle, because ``test`` environment has all the
    needed requirements for Mink and default ``SymfonyDriver`` enabled out
    of the box.

Available Options
~~~~~~~~~~~~~~~~~

MinkBundle provides bunch of useful options for you to configure Mink's
behavior. You can use them to make your testing experience even more
smooth:

* ``base_url`` - most important one. Defines base url for your application.
  Used heavily inside BehatBundle and can be used inside your test cases to
  be able to use relative paths in your web test cases.

* ``default_session`` - defines session name, which will be used by default. It's
  ``symfony`` out of the box.

* ``browser_name`` - specifies browser to be used with ``Sahi``, ``Selenium`` or ``Selenium2`` sessions.


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
            $this->base = $this->getKernel()
                ->getContainer()
                ->getParameter('mink.base_url');
        }

        // write functional tests
    }

Base ``Behat\MinkBundle\Test\MinkTestCase`` class provides an easy way to get
``$mink`` and specific session instances in your tests:

1. ``symfony`` session will be used by default, so ``getSession()`` without
   parameters will return ``test.client`` enabled session for you:

   .. code-block:: php

     $session = $this->getMink()->getSession();
     // or you can use the more verbose call:
     $session = $this->getMink()->getSession('symfony');

2. If you want to test your application with **real** HTTP requests, you should
   use ``goutte`` session instead (should be enabled in ``config_test.yml``
   first):

   .. code-block:: php

     $session = $this->getMink()->getSession('goutte');

3. If you want to test your app running in real browser - use ``sahi``
   session (should be enabled in ``config_test.yml`` first):

   .. code-block:: php

     $session = $this->getMink()->getSession('sahi');

3. If you want to test your app running in zombie.js browser - use ``zombie``
   session (should be enabled in ``config_test.yml`` first):

   .. code-block:: php

     $session = $this->getMink()->getSession('zombie');

After you've choosen needed session - use it to perform actions on your
Symfony2 app:

.. code-block:: php

    $session
        ->visit($this->base.'/_behat/tests/page/page1');
    $this->assertTrue(
        $session->getPage()->hasContent('Page N1')
    );

    $session->getPage()->clickLink('p10');

For example, form specification with ``symfony`` session will look like that:

.. code-block:: php

    public function testForms()
    {
        $session = $this->getMink()->getSession();

        $session->visit($this->base.'/_behat/tests/form');
        $page = $session->getPage();

        // 3. FILL FORMS:

        $page->fillField('name', 'ever');
        $page->fillField('age', '23');
        $page->selectFieldOption('speciality', 'programmer');
        $page->pressButton('Send spec info');

        // 4. ASSERT RESPONSE:

        $this->assertTrue(
            $page->hasContent('POST recieved')
        );
        $this->assertTrue(
            $page->hasContent('ever is 23 years old programmer')
        );
    }
