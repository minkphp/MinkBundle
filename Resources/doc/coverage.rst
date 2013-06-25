Code coverage
=============

test.php

.. code-block:: php

        <?php

        use Symfony\Component\HttpFoundation\Request;

        // start collect coverage from functional tests
        require_once  __DIR__ . '/../vendor/behat/mink-bundle/Behat/MinkBundle/Coverage/prepend.php';

        // If you don't want to setup permissions the proper way, just uncomment the following PHP line
        // read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
        //umask(0000);

        // This check prevents access to debug front controllers that are deployed by accident to production servers.
        // Feel free to remove this, extend it, or make something more sophisticated.
        if (isset($_SERVER['HTTP_CLIENT_IP'])
            || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
        ) {
            header('HTTP/1.0 403 Forbidden');
            exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
        }

        $loader = require_once __DIR__.'/../app/bootstrap.php.cache';
        require_once __DIR__.'/../app/AppKernel.php';

        $kernel = new AppKernel('test', true);
        $kernel->loadClassCache();
        Request::enableHttpMethodParameterOverride();
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);

        // end collect coverage from functional tests
        require 'PHPUnit/Extensions/SeleniumCommon/append.php';


config_test.yml

.. code-block:: yml

        parameters:
                server_name: localhost
                mink.base_url: 'http://%server_name%/test.php'
                mink.coverage_script_url: 'http://%server_name%/bundles/mink/phpunit_coverage.php'

NOTE
----


For selenium(2) tests need additional functional in the test:


.. code-block:: php

        $this->session->visit('http://localhost');
        $this->initTestCoverage(); // Set cookie for selenium test coverage!!!
        $this->session->visit('http://localhost');
