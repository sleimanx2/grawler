# Grawler

[![Software License][ico-license]](LICENSE.md) [![Build Status](https://travis-ci.org/sleimanx2/grawler.svg?branch=master)](https://travis-ci.org/sleimanx2/grawler)

## Install

Via Composer

``` bash
$ composer require sleimanx2/grawler
```

## Basic Usage


##### getting the page dom

```php
require_once('vendor/autoload.php');

$client = new Bowtie\Grawler\Client();

$grawler = $client->download('http://example.com');
```

##### finding basic attributes


```php
$grawler->title();
```

```php
// provide a css path to find the attribute
$grawler->body($path = '.main-content');
```

##### finding media

```php
$grawler->images('.content img');
```

```php
$grawler->videos('iframe');
```

```php
$grawler->audio('.audio iframe');
```

## Resolving media attributes

In order resolve media attributes you need to [load providers's configuration](#grawler-config)

#### videos

Current video resolvers (youtube , vimeo)

```php
// resolve all videos at once 
$videos = $grawler->videos('iframe')->resolve();
```
then you can access videos attributes as follow
```php
foreach($videos as $video)
{
  $video->id; // the video provider id
  $video->title;
  $video->description;
  $video->url;
  $video->embedUrl;
  $video->images; // Collection of Image instances
  $video->author;
  $video->authorId;
  $video->duration;
  $video->provider; //video source
}
```

you can also resolve videos individually  as follow

```php
$videos = $grawler->videos('iframe')

foreach($videos as $video)
{
	$video->resolve();
    $video->title;
    //...
}
```

#### audio

Current video resolvers (soundcloud)

```php
// resolve all audio at once 
$audio = $grawler->audio('.audio iframe')->resolve();
```
then you can access videos attributes as follow
```php
foreach($audio as $track)
{
  $track->id; // the video provider id
  $track->title;
  $track->description;
  $track->url;
  $track->embedUrl;
  $track->images; // Collection of cover photo instances
  $track->author;
  $track->authorId;
  $track->duration;
  $track->provider; //video source
}
```

you can also resolve audio individually  as follow

```php
$track = $grawler->track('.audio iframe')

foreach($audio as $track)
{
	$track->resolve();
    $track->title;
    //...
}
```


## Resolving page urls


```php
$track = $grawler->links('.main thumb a')

foreach($links as $link)
{
	print $link
    //or
    print $link->uri
    //or
    print $link->getUri()
}
```



## Configuration

### Client Config

##### Set user agent
```php
$client->agent('Googlebot/2.1')->download('http://example.com');
```
Recomended : http://webmasters.stackexchange.com/questions/6205/what-user-agent-should-i-set

##### Set request auth

```php
$client->auth('me', '**')
```
you can change the auth type as follow

```php
$client->auth('me', '**', $type = 'basic');
```

##### Set request method

```php
$client->method('post');
```

### <a name="grawler-config"></a> Grawler config

By default the grawler tries to access those environment variables
```
GRAWLER_YOUTUBE_KEY

GRAWLER_VIMEO_KEY
GRAWLER_VIMEO_SECRET

GRAWLER_SOUNDCLOUD_KEY
GRAWLER_SOUNDCLOUD_SECRET
```
if you don't use env vars  you can load configuration as follow.

```php
$config = [
'youtubeKey'   =>'',
'soundcloudKey'=>''

'vimeoKey'    => '',
'vimeoSecret' => '',

'soundcloudKey'    => '',
'soundcloudSecret' => '',
];

$grawler->loadConfig($config);
```


## Testing


``` bash
$ phpunit --testsuite unit
```

``` bash
$ phpunit --testsuite integration
```

NB: you should set your ptoviders key (youtube,vimeo,soundcloud...) to run integration tests

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md)

## Security

If you discover any security related issues, please email sleiman@bowtie.land instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
