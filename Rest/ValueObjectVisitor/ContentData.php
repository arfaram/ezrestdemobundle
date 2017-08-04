<?php

namespace Ez\RestBundle\Rest\ValueObjectVisitor;

use eZ\Publish\Core\REST\Common\Output\Generator;
use eZ\Publish\Core\REST\Common\Output\ValueObjectVisitor;
use eZ\Publish\Core\REST\Common\Output\Visitor;

class ContentData extends ValueObjectVisitor
{
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $visitor->setHeader('Content-Type', $generator->getMediaType('ContentList'));

        if (empty($data->contents)) {
            $visitor->setStatus(204);

            return;
        }

        //The render and generateElement can use compiler pass each time this class is called, only for demo purpose
        $responseData = $this->render($generator, $data);

        return $responseData;
    }

    /**
     * @param Generator $generator
     * @param $data
     * @return Generator
     */
    public function render(Generator $generator, $data)
    {
        $contents = array();
        foreach ($data->contents as $contentTypes) {
            foreach ($contentTypes as $contentType) {
                $contents[] = $contentType;
            }
        }

        return $this->generateElement($generator, $contents);
    }

    /**
     * @param Generator $generator
     * @param array $contentTypeItems
     * @return Generator
     */
    public function generateElement(Generator $generator, array $contentTypeItems = [])
    {
        $generator->startObjectElement('contentList');
        $generator->startList('content');

        foreach ($contentTypeItems as $content) {
            $generator->startObjectElement('content');

            $generator->startValueElement('contentId', $content['contentId']);
            $generator->endValueElement('contentId');

            $generator->startValueElement('contentTypeId', $content['contentTypeId']);
            $generator->endValueElement('contentTypeId');

            $generator->startValueElement('identifier', $content['identifier']);
            $generator->endValueElement('identifier');

            $generator->startValueElement('language', $content['language']);
            $generator->endValueElement('language');

            $generator->startValueElement('publishedDate', $content['publishedDate']);
            $generator->endValueElement('publishedDate');

            $generator->startValueElement('uri', $content['uri']);
            $generator->endValueElement('uri');

            $generator->startValueElement('categoryPath', $content['categoryPath']);
            $generator->endValueElement('categoryPath');

            $generator->startObjectElement('mainLocation');
            $generator->startAttribute('href', $content['mainLocation']['href']);
            $generator->endAttribute('href');
            $generator->endObjectElement('mainLocation');

            $generator->startObjectElement('locations');
            $generator->startAttribute('href', $content['locations']['href']);
            $generator->endAttribute('href');
            $generator->endObjectElement('locations');

            foreach ($content['fields'] as $identifier => $field) {
                $generator->startValueElement($identifier, $field);
                $generator->endValueElement($identifier);
            }

            $generator->endObjectElement('content');
        }

        $generator->endList('content');
        $generator->endObjectElement('contentList');

        return $generator;
    }
}
