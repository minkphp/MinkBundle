<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();
$collection->add(
    '_behat_tests_page',
    new Route(
        '/_behat/tests/page/{page}',
        array(
            '_controller' => 'Behat\MinkBundle\Tests\Controller\TestsController::pageAction',
        )
    )
);
$collection->add(
    '_behat_tests_redirect',
    new Route(
        '/_behat/tests/redirect',
        array(
            '_controller' => 'Behat\MinkBundle\Tests\Controller\TestsController::redirectAction',
        )
    )
);
$collection->add(
    '_behat_tests_form',
    new Route(
        '/_behat/tests/form',
        array(
            '_controller' => 'Behat\MinkBundle\Tests\Controller\TestsController::formAction',
        )
    )
);
$collection->add(
    '_behat_tests_submit',
    new Route(
        '/_behat/tests/submit',
        array(
            '_controller' => 'Behat\MinkBundle\Tests\Controller\TestsController::submitAction',
        )
    )
);
$collection->add(
    '_behat_tests_headers',
    new Route(
        '/_behat/tests/headers',
        array(
            '_controller' => 'Behat\MinkBundle\Tests\Controller\TestsController::headersAction',
        )
    )
);

return $collection;
