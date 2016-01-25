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
$client = Client();

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

### Grawler config

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

