<?php

namespace Ez\RestDemoBundle\Content;

use EzSystems\EzPlatformRichTextBundle\eZ\RichText\Converter\Html5 as RichHtml5;
use eZ\Bundle\EzPublishCoreBundle\Imagine\ImageAsset\AliasGenerator as ImageVariationService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\FieldTypeService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as UrlGenerator;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\MVC\Symfony\SiteAccess;
use eZ\Publish\Core\MVC\Exception\SourceImageNotFoundException;

class Content
{
    /** @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface */
    protected $generator;

    /** @var \eZ\Publish\Core\Repository\LocationService */
    protected $locationService;

    /** @var \eZ\Publish\Core\Repository\SearchService */
    protected $searchService;

    /** @var \eZ\Publish\Core\MVC\Symfony\SiteAccess */
    protected $siteAccess;

    /** @var \eZ\Publish\Core\Repository\ContentTypeService */
    protected $contentTypeService;

    /** @var \eZ\Publish\Core\Repository\FieldTypeService */
    protected $fieldTypeService;

    /** @var \eZ\Bundle\EzPublishCoreBundle\FieldType\RichText\Converter\Html5 */
    protected $xmlHtml5Converter;

    /** @var \eZ\Bundle\EzPublishCoreBundle\Imagine\AliasGenerator */
    protected $imageVariationService;

    /**
     * Content constructor.
     *
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $generator
     * @param \eZ\Publish\API\Repository\LocationService $locationService
     * @param \eZ\Publish\API\Repository\SearchService $searchService
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeService
     * @param \eZ\Publish\API\Repository\FieldTypeService $fieldTypeService
     * @param \EzSystems\EzPlatformRichTextBundle\eZ\RichText\Converter\Html5 $richHtml5Converter
     * @param \eZ\Bundle\EzPublishCoreBundle\Imagine\ImageAsset\AliasGenerator $imageVariationService
     */
    public function __construct(
        UrlGenerator $generator,
        LocationService $locationService,
        SearchService $searchService,
        ContentTypeService $contentTypeService,
        FieldTypeService $fieldTypeService,
        RichHtml5 $richHtml5Converter,
        ImageVariationService $imageVariationService
    ) {
        $this->generator = $generator;
        $this->locationService = $locationService;
        $this->searchService = $searchService;
        $this->contentTypeService = $contentTypeService;
        $this->fieldTypeService = $fieldTypeService;
        $this->richHtml5Converter = $richHtml5Converter;
        $this->imageVariationService = $imageVariationService;
    }

    /**
     * @param \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess
     */
    public function setSiteAccess(SiteAccess $siteAccess)
    {
        $this->siteAccess = $siteAccess;
    }

    /**
     * @return \eZ\Publish\Core\MVC\Symfony\SiteAccess
     */
    public function getSiteAccess()
    {
        return $this->siteAccess;
    }

    /**
     * @param array $options
     * @return \eZ\Publish\API\Repository\Values\Content\Search\SearchHit[]
     */
    public function getItems(array $options)
    {
        $contentTypeId = $options['contentTypeId'];

        $criteria = array(new Query\Criterion\ContentTypeId($contentTypeId));

        //$rootLocationId = $this->getConfigResolver()->getParameter( 'content.tree_root.location_id' ); //under the siteaccess root location
        try {
            $criteria[] = new Query\Criterion\Subtree($this->locationService->loadLocation($options['subtree'])->pathString);
        } catch (\Exception $e) {
            echo 'You should specify the subtree location (Mandatory options) Trace:' . $e->getMessage();
            exit;
        }

        if ($options['hidden'] == 'false' || !$options['hidden']) {
            $criteria[] = new Query\Criterion\Visibility(Query\Criterion\Visibility::VISIBLE);
        } else {
            $criteria[] = new Query\Criterion\Visibility(Query\Criterion\Visibility::HIDDEN);
        }

        if ($lang = $options['lang']) {
            $criteria[] = new Query\Criterion\LanguageCode($lang);
        }

        $query = new Query();
        $query->query = new Query\Criterion\LogicalAnd($criteria);

        if ($options['limit']) {
            $query->limit = (int)$options['limit'];
        }

        //e.g sort by name
        if ($sort = $options['sort']) {
            $sortClause = $sort == 'ASC' ? new Query\SortClause\ContentName(Query::SORT_ASC) : new Query\SortClause\ContentName(Query::SORT_DESC);
            $query->sortClauses = array(
                $sortClause,
            );
        }

        $contentItems = $this->searchService->findContent($query)->searchHits;
//print_r($contentItems);exit;
        return $contentItems;
    }

    /**
     * @param $items
     * @param $options
     * @return array|mixed
     */
    protected function prepareContent($items, $options)
    {
        $data = array();
        $i = 0;
        foreach ($items as $contentValue) {
            $contentValue = $contentValue->valueObject;
            $contentType = $this->contentTypeService->loadContentType($contentValue->contentInfo->contentTypeId);
            $location = $this->locationService->loadLocation($contentValue->contentInfo->mainLocationId);
            $language = (null === $options['lang']) ? $location->contentInfo->mainLanguageCode : $options['lang'];

            $data[$i] = array(
                'contentId' => $contentValue->id,
                'contentTypeId' => $contentType->id,
                'identifier' => $contentType->identifier,
                'language' => $language,
                'publishedDate' => $contentValue->contentInfo->publishedDate->format('c'),
                'uri' => $this->generator->generate($location, array(), false),
                'mainLocation' => array(
                    'href' => '/api/ezp/v2/content/locations' . $location->pathString,
                ),
                'locations' => array(
                    'href' => '/api/ezp/v2/content/objects/' . $contentValue->id . '/locations',
                ),
                'categoryPath' => $location->pathString,
                'fields' => array(),
            );

            $data = $this->getFieldValues($data, $i, $contentValue, $contentType, $options);
            ++$i;
        }

        return $data;
    }

    /**
     * @param $data
     * @param $current
     * @param $contentValue
     * @param $contentType
     * @param $options
     * @return mixed
     */
    public function getFieldValues($data, $current, $contentValue, $contentType, $options)
    {
        // iterate over the field definitions of the content type and print out each field's identifier and value
        foreach ($contentType->fieldDefinitions as $fieldDefinition) {
            $fieldType = $this->fieldTypeService->getFieldType($fieldDefinition->fieldTypeIdentifier);
            $field = $contentValue->getField($fieldDefinition->identifier);

            if (isset($options['fields'])) {
                foreach ($options['fields'] as $requestedFieldType) {
                    if ($requestedFieldType == $fieldDefinition->identifier) {
                        // We use the Field's toHash() method to get readable content out of the Field
                        $valueHash = $fieldType->toHash($field->value);
                        if ($fieldDefinition->fieldTypeIdentifier == 'ezrichtext') {
                            $valueHash = $this->ezrichtext($field);
                        }
                        if ($fieldDefinition->fieldTypeIdentifier == 'ezimage') {
                            $valueHash = $this->ezimage($field, $contentValue, $options);
                        }

                        $data[$current]['fields'][$fieldDefinition->identifier] = $valueHash;
                    }
                }
            } else {
                // We use the Field's toHash() method to get readable content out of the Field
                $valueHash = $fieldType->toHash($field->value);
                $data[$current]['fields'][$fieldDefinition->identifier] = $valueHash;
            }
        }

        return $data;
    }

    //Below can be moved to custom service.< only for demo purpose >

    /**
     * Method for parsing ezrichtext field.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     * @return string
     */
    public function ezrichtext($field)
    {
        return '<![CDATA[' . $this->richHtml5Converter->convert($field->value->xml)->saveHTML() . ']]>';
    }

    /**
     * Method for rendering ezimage field.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     * @param $content
     * @param $options
     * @return string
     */
    public function ezimage($field, $content, $options)
    {
        try {
            if (isset($options['image_variation']) && !empty($options['image_variation'])) {
                return $this->imageVariationService->getVariation($field, $content->versionInfo, $options['image_variation'])->uri;
            } else {
                return $this->imageVariationService->getVariation($field, $content->versionInfo, 'original')->uri;
            }
        } catch (SourceImageNotFoundException $exception) {
            return '';
        }
    }
}
