Usage
=====

Registering an API Key
----------------------

You need to [sign up](http://www.pandastream.com/pricing-signup) to receive an
API key. Then, create one or more clouds to store the encoded videos in. Each
cloud is associated an Amazon AWS S3 bucket.

Initializing the API
--------------------

The client library consists of several components that need to be initialized
to access the API. Luckily, the complex task of configuring and initializing
the library is wrapped by the ``Api`` class.

Pass an array with your personal access data and an API host (api.pandastream.com
or api-eu.pandastream.com if your Panda account is in the EU) to its constructor:

```php
use Xabbuh\PandaClient\Api;

$api = new Api(array(
    'accounts' => array(
        'your-account' => array(
            'access_key' => 'your-access-key',
            'secret_key' => 'your-secret-key',
            'api_host' => 'api.pandastream.com',
        ),
    ),
    'clouds' => array(
        'your-account' => array(
            'id' => 'your-cloud-id',
            'account' => 'default',
        ),
    ),
));
```

This way, you can access more than one cloud using the same ``Api`` object
even if they don't belong to the same account. You can then retrieve each of
your configured clouds by passing the name you used in your config above to
the ``getCloud()``:

```php
$cloud = $api->getCloud('your-cloud');
```

Note: If you only have one cloud to use, you can use the static ``getCloudInstance()``
shortcut method:

```php
$cloud = Api::getCloudInstance(
    'your-access-key',
    'your-secret-key',
    'api.pandastream.com',
    'your-cloud-id'
);
```

API Methods
-----------

The returned ``Cloud`` object offers you methods for each endpoint of the Panda
REST API.

For example:

```php
// get a list of all videos
$videos = $cloud->getVideos();

// delete an existing video
$cloud->deleteVideo('a-video-id');
```

See the official [API documentation](http://www.pandastream.com/docs/api) and
the [CloudInterface](../src/Api/CloudInterface.php) for an overview of all
available REST endpoints and their corresponding API methods.