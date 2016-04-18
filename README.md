# PHP statsd gearman metrics collector

[![Build Status](https://travis-ci.org/petrica/php-statsd-gearman.svg?branch=master)](https://travis-ci.org/petrica/php-statsd-gearman)

Gearman metrics collector for statsd written in PHP.

Install using composer:
-----------------------

```shell
composer require petrica/statsd-gearman
```

Requirements:
-------------

* PHP ^5.5

Run with:
---------

```shell
vendor/bin/statsd-console statsd:notify --verbose gauges.yml
```

Please have a look at the main statsd library [here](https://github.com/petrica/php-statsd-system).

Sample config file:
-------------------

The configuration file is pretty straightforward, you specify the gauge class name and class arguments

```yml
gauges:
    gearman:
        class: Petrica\StatsdGearman\Gauge\GearmanGauge
        arguments:
            server: localhost:4730
            timeout: 1
```

Where we have the following parameters:

**server** - Gearman server host and port
```
server: [host]:[port]
```

**timeout** - Connection timeout in seconds
```
timeout: [seconds]
```

