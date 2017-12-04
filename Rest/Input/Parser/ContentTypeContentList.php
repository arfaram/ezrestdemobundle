<?php

namespace Ez\RestBundle\Rest\Input\Parser;

use eZ\Publish\Core\REST\Common\Input\BaseParser;
use eZ\Publish\Core\REST\Common\Input\ParsingDispatcher;
use Ez\RestBundle\Rest\Values\ContentData;
use eZ\Publish\Core\REST\Common\Exceptions;

class ContentTypeContentList extends BaseParser
{
    /**
     * @param array $data
     * @param \eZ\Publish\Core\REST\Common\Input\ParsingDispatcher $parsingDispatcher
     * @return \Ez\RestBundle\Rest\Values\ContentData
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!isset($data['contentTypeId'])) {
            throw new Exceptions\Parser("Missing or invalid 'contentTypeId' element for ContentTypeContentList.");
        }
        //go further with other checks

        return new ContentData($data);
    }
}
