CHANGELOG
=========

1.1.0
-----

* ignore additional properties that are not reflected in the model classes
  during deserealisation

* additional options (a list of profiles to be used, a custom path format a
  payload) can be passed to the methods ``CloudInterface::encodeVideoByUrl()``
  and ``ClientInterface::encodeVideoFile()``

* added a payload property to the ``Video`` model (by @tgallice in #2)
