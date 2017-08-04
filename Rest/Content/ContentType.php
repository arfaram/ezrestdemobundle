<?php

namespace Ez\RestBundle\Rest\Content;

class ContentType extends Content
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $responseType
     *
     * @return array
     */
    public function parseContentTypeRequest($request, $responseType = 'http')
    {
        $requestedFieldTypes = $request->get('fields');
        $requestOptions = array(
            'responseType' => $responseType,
            'siteAccess' => $request->get('sa', $this->getSiteAccess()->name),
            'limit' => $request->get('limit'),
            'sort' => $request->get('sort'),
            'lang' => $request->get('lang'),
            'hidden' => $request->get('hidden'),
            'subtree' => $request->get('subtree'),
        );

        if (isset($requestedFieldTypes) && !empty($requestedFieldTypes)) {
            $requestOptions['fields'] = explode(',', $requestedFieldTypes);
        }

        return $requestOptions;
    }

    /**
     * Prepare content array.
     *
     * @param array $data
     * @param array $options
     *
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
