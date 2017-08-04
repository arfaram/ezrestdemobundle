<?php

namespace Ez\RestBundle\Rest\Controller;

use eZ\Publish\Core\REST\Server\Controller as BaseController;
use Ez\RestBundle\Rest\Content\ContentType;
use Ez\RestBundle\Rest\Values\ContentData as ContentDataValue;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContentController.
 */
class ContentController extends BaseController
{
    /** @var \Ez\RestBundle\Rest\Content\ContentType */
    protected $contentType;

    /*
     * Content constructor.
     * @param \Ez\RestBundle\Rest\Content\ContentType $contentType
     */
    public function __construct(
        ContentType $contentType
    ) {
        $this->contentType = $contentType;
    }

    /**
     * @param $contentIdList
     * @param Request $request
     * @return mixed
     */
    public function getContentTypeContent($contentTypeId, Request $request)
    {
        $options = $this->contentType->parseContentTypeRequest($request);
        $options['contentTypeId'] = $contentTypeId;

        $contentItems = array();

        $contentItems[$contentTypeId] = $this->contentType->getItems($options);

        $data = $this->contentType->prepareContentTypes($contentItems, $options);

        return new ContentDataValue($data, $options);
    }
}
