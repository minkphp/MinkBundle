<?php
// By default the code coverage files are written to the same directory
// that contains the covered sourcecode files. Use this setting to change
// the default behaviour and set a specific directory to write the files to.
// If you change the default setting, please make sure to also configure
// the same directory in phpunit_coverage.php. Also note that the webserver
// needs write access to the directory.

$path = realpath(__DIR__.'/../../../../../../app/logs').'/coverage';
@mkdir($path, 0777);
$GLOBALS['PHPUNIT_COVERAGE_DATA_DIRECTORY'] = $path;
