Provides Behat\Mink browser abstraction library for PHPUnit in Symfony2 project.

## Features

- Support for Symfony2 test.client browser emulator
- Support for Goutte browser emulator
- Support for Sahi (JS testing) browser emulator
- Support for Selenium (JS testing) browser emulator
- Support for Selenium2 (WebDriver) browser emulator

## Configuration

config_test.yml
```yaml
behat:
    mink:
        base_url: 'localhost'
        show_cmd: null
        show_tmp_dir: '%kernel.cache_dir%'
        default_session: 'symfony'
        javascript_session: 'sahi'
        browser_name: 'firefox'
        goutte: true
        sahi:
            sid: null
            host: 'localhost'
            port: 9999
        zombie:
            host: '127.0.0.1'
            port: 8124
            auto_server: true
            node_bin: 'node'
        selenium:
            browser: '*firefox'
            host: '127.0.0.1'
            port: 4444
        selenium2:
            browser: '*firefox'
            capabilities:
                browserName: 'firefox'
                version: 8
                platform: 'ANY'
                browserVersion: 8
                browser: 'firefox'
            wd_host: 'http://localhost:4444/wd/hub'

```

## Run tests

Install Symfony 2.2.* project and istall this bundle as usual

### Add routing
routing_test.yml
```yaml
_main:
    resource: routing.yml

_mink_bundle:
    resource: "@MinkBundle/Resources/config/routing.yml"
    prefix:   /
```

And run tests from root of project

```bash
phpunit vendor/behat/mink-bundle/tests/Behat/MinkBundle/Tests/
```

## Documentation

- [Mink Documentation](http://mink.behat.org)
- [MinkBundle Documentation](http://mink.behat.org/bundle/index.html)

