# MinkBundle [![Build Status](https://api.travis-ci.org/Behat/MinkBundle.png)](https://travis-ci.org/Behat/MinkBundle)

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

