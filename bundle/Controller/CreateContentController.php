<?php

namespace Ez\RestDemoBundle\Controller;

use eZ\Publish\Core\REST\Server\Controller as BaseController;
use Ez\RestDemo\Rest\Values\ContentData as ContentDataValue;
use Symfony\Component\HttpFoundation\Request;
use Ez\RestDemoBundle\CreateContentServices\CreateContent;

/**
 * Class CreateContentController
 *
 * @package Ez\RestDemoBundle\Controller
 */
class CreateContentController extends BaseController
{


    /** @var Ez\RestDemoBundle\CreateContentServices\CreateContent */
    protected $createContent;

    /**
     * ContentTypeContentListController constructor.
     *
     * @param \Ez\RestDemo\Rest\Content\ContentType $contentType
     * @param \Ez\RestDemo\Rest\Repository\RequestParserService $requestParserService
     */
    public function __construct(
        CreateContent $createContent
    )
    {
        $this->createContent = $createContent;
    }


    /**
     * ez_rest/create/content Unauthorized: then clear cookie e.g. Postman
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Ez\RestDemo\Rest\Values\ContentData
     * @throws \Exception
     */
    public function createContent(Request $request)
    {
        //Generate Token and set Cookie
        $token = $this->createContent->createSessionToken($request->headers->get('php-auth-user'),$request->headers->get('php-auth-pw'));

        //Create Draft
        $contentInfos = $this->createContent->createDraft(
            $request->getContent(),
            $request->headers->get('content_type'),
            $request->headers->get('accept') ,
            $token
        );

        //Publish the draft
        $this->createContent->publishContent(
            $contentInfos['draftHref'],
            $token
        );

        //loadContent
        $response = $this->createContent->loadContent(
            $contentInfos['currentVersionHref'],
            $request->headers->get('accept')
        );

        print_r($response);
        exit;

    }

}
