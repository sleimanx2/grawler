<?php

namespace Bowtie\Grawler\Nodes;


use InvalidArgumentException;

class MediaCollection extends Collection
{

    /**
     * resolve all media attributes
     */
    public function resolve()
    {
        foreach ($this->items as $item) {
            $item->resolve();
        }
    }


    /**
     * @param $media
     */
    public function add($media)
    {
        $media = $this->isAssocArray($media) ? [$media]  : $media;

        foreach ($media as $offset => $value) {
            $this->set($offset, $value);
        }
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function get($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->items[$offset];
        }
    }

    /**
     * @param $offset
     * @param $value
     */
    public function set($offset, $value)
    {
        if ($value) {
            // only accept Media instance
            if ($value instanceof Media) {

                //if the offset is not an integer add media to the bottom of the collection
                if (!is_int($offset)) {
                    $this->items[] = $value;
                } else {
                    $this->items[$offset] = $value;
                }

            } else {
                throw new InvalidArgumentException;
            }
        }

    }

    /**
     * @param $items
     * @return bool
     */
    private function isAssocArray($items)
    {
        // check if the given argument is an associative array
        return !is_array($items) or array_keys($items) !== range(0, count($items) - 1);
    }

}