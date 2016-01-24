<?php
namespace Bowtie\Grawler\Nodes;


class Image extends Media
{
    /**
     * @var array
     */
    protected $attributes = [
        'id',
        'description',
        'url',
        'width',
        'height'
    ];

    /**
     * @var array
     */
    protected $resolvers = [];
}