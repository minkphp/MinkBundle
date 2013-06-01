# MinkBundle [![Build Status](https://api.travis-ci.org/Behat/MinkBundle.png?branch=master)](https://travis-ci.org/Behat/MinkBundle)

Provides Behat\Mink browser abstraction library for PHPUnit in Symfony2 project.

## Features

- Symfony2 test.client browser emulator
- Goutte browser emulator
- Zombie (JS testing) browser emulator - To be done
- Selenium2 (WebDriver) browser emulator
- Selenium (JS testing) browser emulator
- Sahi (JS testing) browser emulator - Not tested

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

- [Installation](/Behat/MinkBundle/blob/master/Resources/doc/index.rst)
- [Mink Documentation](http://mink.behat.org)
- [PHPUnit Documentation](http://www.phpunit.de/manual/current/en/index.html)

