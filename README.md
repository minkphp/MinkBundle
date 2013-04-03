Provides Behat\Mink browser abstraction library for PHPUnit in Symfony2 project.

## Features

- Symfony2 test.client browser emulator - In progress
- Goutte browser emulator - Works
- Zombie (JS testing) browser emulator - To be done
- Selenium2 (WebDriver) browser emulator - Works
- Selenium (JS testing) browser emulator - Forgotten
- Sahi (JS testing) browser emulator - Not tested

## Configuration

config_test.yml
```yaml
mink:
    base_url: 'http://localhost'
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

## Run tests

Install Symfony 2.2.* project and install this bundle as usual

### Add routing
routing_test.yml
```yaml
_main:
    resource: routing.yml

_mink_bundle:
    resource: "@MinkBundle/Resources/config/routing.yml"
    prefix:   /
```

And run tests from the root of the project

```bash
phpunit vendor/behat/mink-bundle/tests/Behat/MinkBundle/Tests/
```

## Documentation

- [Mink Documentation](http://mink.behat.org)
- [PHPUnit Documentation](http://www.phpunit.de/manual/current/en/index.html)

