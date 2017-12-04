<?php

namespace Ez\RestBundle\Rest\Repository;

use eZ\Publish\Core\MVC\Symfony\SiteAccess;
use eZ\Publish\Core\REST\Common\Input\Dispatcher;

class RequestParserService
{
    /** @var \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess */
    private $siteAccess;

    /** @var $responseRenderers */
    private $responseRenderers = [];

    /** @var \eZ\Publish\Core\REST\Common\Input\Dispatcher $InputDispatcher */
    public $InputDispatcher;

    /**
     * @param $requestParser
     */
    public function getRequest($requestParser)
    {
        $this->requestParser = $requestParser;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return mixed
     */
    public function parse($request, $verbs, $inputParser = null)
    {
        return $this->requestParser[$verbs]->parseRequestData($request, $this->getSiteAccess(), $inputParser);
    }

    /**
     * @param \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess
     */
    public function setSiteAccess(SiteAccess $siteAccess = null)
    {
        $this->siteAccess = $siteAccess;
    }

    /**
     * @return \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess
     */
    public function getSiteAccess()
    {
        return $this->siteAccess;
    }

    /**
     * @param \eZ\Publish\Core\REST\Common\Input\Dispatcher $dispatcher
     */
    public function setInputDispatcher(Dispatcher $dispatcher)
    {
        $this->InputDispatcher = $dispatcher;
    }

    /**
     * @return \eZ\Publish\Core\REST\Common\Input\Dispatcher
     */
    public function getInputDispatcher()
    {
        return $this->InputDispatcher;
    }
}
