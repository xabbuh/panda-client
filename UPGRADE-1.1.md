Upgrade from 1.0 to 1.1
=======================

The `encodeVideoByUrl()` and `encodeVideoFile()` method got additional parameters.
If you implement this interface, you'll have to reflect these changes.

Before:

```php
// ...

public function encodeVideoByUrl($url)
{
    // ...
}

public function encodeVideoFile($localPath)
{
    // ...
}

// ...
```

After:

```php
// ...

public function encodeVideoByUrl($url, array $profiles = array(), $pathFormat = null, $payload = null)
{
    // ...
}

public function encodeVideoFile($localPath, array $profiles = array(), $pathFormat = null, $payload = null)
{
    // ...
}

// ...
```
