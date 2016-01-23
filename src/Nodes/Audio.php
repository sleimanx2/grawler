<?php
namespace Bowtie\Grawler\Nodes;

use Bowtie\Grawler\Nodes\Resolvers\SoundCloudResolver;

class Audio extends Media
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
        SoundCloudResolver::class,
    ];

}