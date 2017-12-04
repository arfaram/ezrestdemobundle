<?php

namespace Ez\RestBundle\Rest\Controller;

use eZ\Publish\Core\REST\Server\Controller as BaseController;
use eZ\Publish\Core\REST\Server\Values\CachedValue;
use Ez\RestBundle\Rest\Content\ContentType;
use Ez\RestBundle\Rest\Repository\RequestParserService;
use Ez\RestBundle\Rest\Values\ContentData as ContentDataValue;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContentTypeContentListController.
 */
class ContentTypeContentListController extends BaseController
{
    /** @var \Ez\RestBundle\Rest\Content\ContentType */
    protected $contentType;

    /** @var \Ez\RestBundle\Rest\Repository\RequestParserService */
    protected $requestParserService;

    /**
     * ContentTypeContentListController constructor.
     *
     * @param \Ez\RestBundle\Rest\Content\ContentType $contentType
     * @param \Ez\RestBundle\Rest\Repository\RequestParserService $requestParserService
     */
    public function __construct(
        ContentType $contentType,
        RequestParserService $requestParserService
    ) {
        $this->contentType = $contentType;
        $this->requestParserService = $requestParserService;
    }

    /**
     * @param $contentTypeId
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \eZ\Publish\Core\REST\Server\Values\CachedValue
     */
    public function getContentTypeContent($contentTypeId, Request $request)
    {
        $options = $this->requestParserService->parse($request, $request->getMethod());

        $options['contentTypeId'] = $contentTypeId;

        $contentItems = array();

        $contentItems[$contentTypeId] = $this->contentType->getItems($options);

        $data = $this->contentType->prepareContentTypes($contentItems, $options);

        //Option1
        //return new JsonResponse(array('name' => $data));
        //Option2: Send without caching
        //return new ContentDataValue($data, $options);

        return new CachedValue(
            new ContentDataValue($data, $options),
            array('locationId' => $options['subtree'])
        );
    }

    /**
     * Method using POST Request.
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Ez\RestBundle\Rest\Values\ContentData
     */
    public function getContentTypeContentPost(Request $request)
    {
        //$request->getContent()  contains the XML or JSON and will be passer to the input parser

        $inputParser = $this->requestParserService->getInputDispatcher();
        $options = $this->requestParserService->parse($request, $request->getMethod(), $inputParser);

        $contentItems = array();

        $contentItems[$options['contentTypeId']] = $this->contentType->getItems($options);

        $data = $this->contentType->prepareContentTypes($contentItems, $options);

        return new ContentDataValue($data, $options);
    }
}
