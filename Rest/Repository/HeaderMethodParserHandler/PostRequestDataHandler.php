<?php

namespace Ez\RestBundle\Rest\Repository\HeaderMethodParserHandler;

use eZ\Publish\Core\REST\Common\Message;
use Ez\RestBundle\Rest\Repository\RequestParserInterface;

class PostRequestDataHandler implements RequestParserInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess
     * @param \eZ\Publish\Core\REST\Common\Input\Dispatcher $dispatcher
     * @param string $responseType
     * @return array
     * @throws \Exception
     */
    public function parseRequestData($request, $siteAccess, $dispatcher, $responseType = 'http')
    {
        $options = $dispatcher->parse(
            new Message(
                array('Content-Type' => $request->headers->get('Content-Type')),
                $request->getContent()
            )
        );

        $requestedFieldTypes = array_key_exists('fields', $options->contents) ? $options->contents['fields'] : '';
        $requestOptions = array(
            'responseType' => $responseType,
            'contentTypeId' => $options->contents['contentTypeId'], //Is checked with the Input Parser
            'siteAccess' => $request->get('sa', $siteAccess->name),
            'limit' => array_key_exists('limit', $options->contents) ? $options->contents['limit'] : '',
            'sort' => array_key_exists('sort', $options->contents) ? $options->contents['sort'] : '',
            'lang' => array_key_exists('lang', $options->contents) ? $options->contents['lang'] : '',
            'hidden' => array_key_exists('hidden', $options->contents) ? $options->contents['hidden'] : '',
            'subtree' => array_key_exists('subtree', $options->contents) ? $options->contents['subtree'] : '',
            'image_variation' => array_key_exists('image_variation', $options->contents) ? $options->contents['image_variation'] : '',
        );

        if (isset($requestedFieldTypes) && !empty($requestedFieldTypes)) {
            foreach ($requestedFieldTypes['field'] as $fields) {
                $requestOptions['fields'][] = $fields['fieldDefinitionIdentifier'];
            }
        } else {
            throw new \Exception('Missing "fields" POST payload.');
        }
        //print_r($requestOptions);exit;
        return $requestOptions;
    }
}
