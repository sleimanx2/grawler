<?php

namespace Bowtie\Grawler\Config;


use Noodlehaus\AbstractConfig;

class Config extends AbstractConfig
{

    protected function getDefaults()
    {
        return [
            'youtubeKey' => getenv('GRAWLER_YOUTUBE_KEY'),

            'vimeoKey'    => getenv('GRAWLER_VIMEO_KEY'),
            'vimeoSecret' => getenv('GRAWLER_VIMEO_SECRET'),

            'soundcloudKey'    => getenv('GRAWLER_SOUNDCLOUD_KEY'),
            'soundcloudSecret' => getenv('GRAWLER_SOUNDCLOUD_SECRET'),
        ];
    }

}