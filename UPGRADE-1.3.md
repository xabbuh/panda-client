Upgrade from 1.2 to 1.3
=======================

* the Panda client relies on the HTTPlug library to perform API requests,
  you need to install a [client implementation](https://packagist.org/providers/php-http/client-implementation).

  For example:

  ```bash
  $ composer require xabbuh/panda-client php-http/guzzle6-adapter
  ```

* the `HttpClient` class is deprecated and will be removed in 2.0, use
  `HttplugClient` instead
