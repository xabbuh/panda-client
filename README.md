PandaClient
===========

[![Build Status](https://secure.travis-ci.org/xabbuh/PandaClient.png?branch=master)](http://travis-ci.org/xabbuh/PandaClient)

The PandaClient package provides an easy to use implementation of the
[Panda encoding services](https://www.pandastream.com/) REST API.

Installation
------------

The recommended way to install the Panda client is through
[Composer](http://getcomposer.org/).

1. Add ``xabbuh/panda-client`` as a dependency in your project's
``composer.json`` file:

    ``` yaml
    {
        "require": {
            "xabbuh/panda-client": "dev-master"
        }
    }
    ```

2. Download and install Composer:

        curl -s http://getcomposer.org/installer | php

    For more details on how to install composer have a look at the official
    [documentation](http://getcomposer.org/doc/00-intro.md).

3. Install your dependencies:

        php composer.phar install

4. Require Composer's autoloader:

    ``` php
    require "vendor/autoloader.php";
    ```

Usage
-----

First, you need to [sign up](http://www.pandastream.com/pricing-signup)
to receive and API key.

To make use of the PHP API you need to create a RESTClient passing your
API credentials to its constructor:

``` php
use Xabbuh\PandaClient\RestClient;

$cloudId = "your-cloud-id";
$accessKey = "your-access-key";
$secretKey = "your-secret-key";
$apiHost = "api.pandastream.com";
// use api-eu.pandastream.com if your Panda account is in the EU

$restClient = new RestClient($cloudId, $accessKey, $secretKey, $apiHost);
```

Have a look at the [RESTClientInterface](https://github.com/xabbuh/PandaClient/blob/master/RestClientInterface.php)
if you want to create your own REST client implementation.

Create your Api object by passing the created REST client to its constructor.

```php
use Xabbuh\PandaClient\Api;
use Xabbuh\PandaClient\RestClient;

$cloudId = "your-cloud-id";
$accessKey = "your-access-key";
$secretKey = "your-secret-key";
$apiHost = "api.pandastream.com";
// use api-eu.pandastream.com if your Panda account is in the EU

$restClient = new RestClient($cloudId, $accessKey, $secretKey, $apiHost);

$api = new Api($restClient);

// get a list of all videos
$videos = $api->getVideos();

// delete an existing video
$api->deleteVideo("1a8215f6911e7e3e22e294f98bbf46dc");
```

See the official [API documentation](http://www.pandastream.com/docs/api)
and the [ApiInterface](https://github.com/xabbuh/PandaClient/blob/master/ApiInterface.php)
for an overview of all available API methods.

Licence
-------

This package is under the MIT licence. See the complete licence in the
[LICENCE](https://github.com/xabbuh/PandaClient/blob/master/LICENSE) file.
