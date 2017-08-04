<?php

namespace Ez\RestBundle\Rest\Values;

class ContentData
{
    /** @var array */
    public $contents;

    /**
     * Constructs ContentData object.
     *
     * @param array $contents
     */
    public function __construct(array $contents)
    {
        $this->contents = $contents;
    }
}
