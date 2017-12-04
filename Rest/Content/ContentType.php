<?php

namespace Ez\RestBundle\Rest\Content;

class ContentType extends Content
{
    /**
     * Prepare content array.
     *
     * @param $data
     * @param $options
     * @return array
     */
    public function prepareContentTypes($data, $options)
    {
        $contentItems = array();

        foreach ($data as $contentTypeId => $items) {
            $contentItems[$contentTypeId] = $this->prepareContent($items, $options);
        }

        return $contentItems;
    }
}
