<?php

namespace Bowtie\Grawler\Nodes;


use Bowtie\Grawler\Nodes\Resolvers\VimeoResolver;
use Bowtie\Grawler\Nodes\Resolvers\YoutubeResolver;

class Video extends Media
{
    /**
     * @var array
     */
    protected $attributes = [
        'id',
        'title',
        'description',
        'url',
        'embedUrl',
        'images',
        'author',
        'authorId',
        'duration',
        'provider',
    ];

    /**
     * @var array
     */
    protected $resolvers = [
        YoutubeResolver::class,
        VimeoResolver::class
    ];
}