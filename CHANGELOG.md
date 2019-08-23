CHANGELOG
=========

1.4.0
-----

* added support for HTTPlug

1.3.0
-----

* the Panda client relies on the HTTPlug library to perform API requests,
  you need to install a [client implementation](https://packagist.org/providers/php-http/client-implementation).

  For example:

  ```bash
  $ composer require xabbuh/panda-client php-http/guzzle6-adapter
  ```

* the `HttpClient` class is deprecated and will be removed in 2.0, use
  `HttplugClient` instead

* allow Symfony 4.x components

* dropped support for PHP 5.3, 5.4, and 5.5

1.2.3
-----

* compatibility with PHP 7.1

1.2.2
-----

* compatibility with Symfony 3.0 components

1.2.1
-----

This release is the same as `1.2.0` as the tag was created on the wrong
commit hash.

1.2.0
-----

* added API method to only delete the source file from the storage

1.1.1
-----

* update installation instructions regarding the BC break introduced in 1.1.0

1.1.0
-----

* ignore additional properties that are not reflected in the model classes
  during deserealisation

* additional options (a list of profiles to be used, a custom path format a
  payload) can be passed to the methods ``CloudInterface::encodeVideoByUrl()``
  and ``ClientInterface::encodeVideoFile()``

* added a payload property to the ``Video`` model (by @tgallice in #2)
