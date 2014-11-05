# MinkBundle

[![Build Status](https://travis-ci.org/Behat/MinkBundle.svg)](https://travis-ci.org/Behat/MinkBundle)
[![Latest Stable Version](https://poser.pugx.org/behat/mink-bundle/version.svg)](https://packagist.org/packages/behat/mink-bundle)
[![Latest Unstable Version](https://poser.pugx.org/behat/mink-bundle/v/unstable.svg)](//packagist.org/packages/behat/mink-bundle)
[![Total Downloads](https://poser.pugx.org/behat/mink-bundle/downloads.svg)](https://packagist.org/packages/behat/mink-bundle)
[![License](https://poser.pugx.org/behat/mink-bundle/license.svg)](https://packagist.org/packages/behat/mink-bundle)

Provides Behat\Mink browser abstraction library for PHPUnit in Symfony2 project.

## Features

- Symfony2 test.client browser emulator
- Goutte browser emulator
- Zombie (JS testing) browser emulator - Have a bug
- Selenium2 (WebDriver) browser emulator
- Selenium (JS testing) browser emulator
- Sahi (JS testing) browser emulator

## Configuration

config_test.yml
```yaml
mink:
    base_url: 'http://localhost'
    browser_name: firefox
    goutte: ~ # optional
    sahi: ~ # optional
    zombie: ~ # optional
    selenium: ~ # optional
    selenium2: ~ # optional

```

All options can be overwrited in parameters.yml
```yaml
mink.base_url: 'http://myhost.com'
mink.browser_name: 'chrome'
```

## Documentation

- [Installation](/Resources/doc/index.rst)
- [Code coverage](/Resources/doc/coverage.rst)
- [Mink Documentation](http://mink.behat.org)
- [PHPUnit Documentation](http://www.phpunit.de/manual/current/en/index.html)

