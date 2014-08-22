PandaClient
===========

[![Build Status](https://travis-ci.org/xabbuh/panda-client.png?branch=master)](https://travis-ci.org/xabbuh/panda-client)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/xabbuh/panda-client/badges/quality-score.png?s=e8204a1032cc74bef0c5e538c17b19d33f67e79c)](https://scrutinizer-ci.com/g/xabbuh/panda-client/)

The PandaClient package provides an easy to use implementation of the
[Panda encoding services](https://www.pandastream.com/) REST API.

Installation
------------

The recommended way to install the Panda client is using
[Composer](http://getcomposer.org/):

1. [Download and install](http://getcomposer.org/doc/00-intro.md) Composer.

1. Add ``xabbuh/panda-client`` as a dependency of your project:

    ```bash
    $ composer require xabbuh/panda-client "~1.0"
    ```

1. And require Composer's autoloader:

   ``` php
   require_once __DIR__.'/vendor/autoload.php';
   ```

Usage
-----

Read the [documentation](doc/usage.md) to find out how to use the library. Refer
to the [library's API documentation](http://dev.xabbuh.de/docs/panda-client/) for
a full reference.

License
-------

This package is under the MIT license. See the complete license in the
[LICENSE](LICENSE) file.
