# Grawler

[![Software License][ico-license]](LICENSE.md) [![Build Status](https://travis-ci.org/sleimanx2/grawler.svg?branch=master)](https://travis-ci.org/sleimanx2/grawler)

## Install

Via Composer

``` bash
$ composer require sleimanx2/grawler
```

## Usage


###getting the page dom

```
$client = Client();

$grawler = $client->download('http://example.com');

```
###finding basic attributes


```
$grawler->title();

```
```
// provide a css path to find the attribute
$grawler->body($path = '.main-content');

```

### finding media

```
$grawler->images('.content img');

```

```
$grawler->videos('iframe');

```

```
$grawler->audio('.audio iframe');

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

If you discover any security related issues, please email sleiman/[at]/bowtie/[dot]/land instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

