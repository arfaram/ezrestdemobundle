<?php

namespace Ez\RestBundle\Rest\Repository;

interface RequestParserInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess
     * @param \eZ\Publish\Core\REST\Common\Input\Dispatcher $inputParser
     */
    public function parseRequestData($request, $siteAccess, $inputParser);
}
