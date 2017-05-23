PandaClient
===========

[![Build Status](https://travis-ci.org/xabbuh/panda-client.svg?branch=master)](https://travis-ci.org/xabbuh/panda-client)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/xabbuh/panda-client/badges/quality-score.png?s=e8204a1032cc74bef0c5e538c17b19d33f67e79c)](https://scrutinizer-ci.com/g/xabbuh/panda-client/)
[![Code Coverage](https://scrutinizer-ci.com/g/xabbuh/panda-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/xabbuh/panda-client/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ddfd19e8-fe75-4b16-aeaf-1947cebaf0ce/mini.png)](https://insight.sensiolabs.com/projects/ddfd19e8-fe75-4b16-aeaf-1947cebaf0ce)
[![Code Climate](https://codeclimate.com/github/xabbuh/panda-client/badges/gpa.svg)](https://codeclimate.com/github/xabbuh/panda-client)

The PandaClient package provides an easy to use implementation of the
[Panda encoding services](https://www.pandastream.com/) REST API.

**Caution:** Version 1.1.0 introduced a BC break for users implementing the
`CloudInterface`. Read the [upgrade instructions](UPGRADE-1.1.md) for more
information.

Installation
------------

The recommended way to install the Panda client is using
[Composer](http://getcomposer.org/):

1. [Download and install](http://getcomposer.org/doc/00-intro.md) Composer.

1. Add ``xabbuh/panda-client`` as a dependency of your project:

    ```bash
    $ composer require xabbuh/panda-client php-http/guzzle6-adapter
    ```

1. And require Composer's autoloader:

   ``` php
   require __DIR__.'/vendor/autoload.php';
   ```
   
Note: The Panda client relies on [HTTPlug](http://httplug.io/) to perform HTTP requests.
So you will need to install a [client implementation](https://packagist.org/providers/php-http/client-implementation)
to use the PandaClient. The command above uses the Guzzle 6 adapter, but you can use
any implementation.

Usage
-----

Read the [documentation](doc/usage.md) to find out how to use the library. Refer
to the [library's API documentation](http://dev.xabbuh.de/docs/panda-client/) for
a full reference.

License
-------

This package is under the MIT license. See the complete license in the
[LICENSE](LICENSE) file.
