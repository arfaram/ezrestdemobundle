<?php

namespace Ez\RestBundle\Rest\Repository\HeaderMethodParserHandler;

use Ez\RestBundle\Rest\Repository\RequestParserInterface;

class GetRequestDataHandler implements RequestParserInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess
     * @param string $responseType
     * @return array
     * @throws \Exception
     */
    public function parseRequestData($request, $siteAccess, $responseType = 'http')
    {
        $requestedFieldTypes = $request->get('fields');
        $requestOptions = array(
            'responseType' => $responseType,
            'siteAccess' => $request->get('sa', $siteAccess->name),
            'limit' => $request->get('limit'),
            'sort' => $request->get('sort'),
            'lang' => $request->get('lang'),
            'hidden' => $request->get('hidden'),
            'subtree' => $request->get('subtree'),
            'image_variation' => $request->get('image_variation'),
        );

        if (isset($requestedFieldTypes) && !empty($requestedFieldTypes)) {
            $requestOptions['fields'] = explode(',', $requestedFieldTypes);
        } else {
            throw new \Exception('Missing "fields" GET param.');
        }
        //print_r($requestOptions);exit;
        return $requestOptions;
    }
}
