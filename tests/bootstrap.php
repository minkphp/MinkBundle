<?php
if (!is_dir($vendor = __DIR__ . '/../vendor')) {
    die('Install dependencies first');
}

require($vendor . '/autoload.php');
require_once(__DIR__ . '/Behat/MinkBundle/Tests/BaseSessionTestCase.php');
