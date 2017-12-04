<?php

namespace Ez\RestBundle\Rest\Values;

class ContentData
{
    /**
     * @var
     */
    public $contents;

    /**
     * ContentData constructor.
     *
     * @param array $contents
     */
    public function __construct(array $contents)
    {
        $this->contents = $contents;
    }
}
