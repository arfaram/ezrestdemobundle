<?php

namespace Ez\RestDemoBundle\CreateContentServices;

/**
 * Class CreateContent
 *
 * @package Ez\RestDemoBundle\CreateContentServices
 */
class CreateContent
{
    /** @var \Ez\RestDemoBundle\CreateContentServices\RestClient  */
    protected $restClient;

    const SESSION_URL = '/api/ezp/v2/user/sessions';
    const CREATE_URL = '/api/ezp/v2/content/objects';
    const STATUS_CREATED = 201;
    const STATUS_NO_CONTENT = 204; //published in eZPlatform

    /**
     * CreateContent constructor.
     *
     * @param \Ez\RestDemoBundle\CreateContentServices\RestClient $restClient
     */
    public function __construct(
        RestClient $restClient
    )
    {
        $this->restClient = $restClient;
    }

    /**
     * Get session Token
     *
     * @param $user
     * @param $password
     * @return string
     * @throws \Exception
     */
    public function createSessionToken($user, $password): string
    {
        $headers =[
            'Content-Type'  => 'application/vnd.ez.api.SessionInput+json',
            'Accept'        => 'application/vnd.ez.api.Session+json',
        ];

        $response = $this->restClient->sendHttpRequest(
            $this->restClient->createPostHeaderHttpRequest(self::SESSION_URL, '{"SessionInput": {"login": "'.$user.'","password": "'.$password.'"}}', $headers  )
        );
        //another way
        //$response = $this->restClient->sendRequest('/api/ezp/v2/user/sessions', '{"SessionInput": {"login": "'.$user.'","password": "'.$password.'"}}', $headers );

        if(isset($response->getStatusCode) && !$response->getStatusCode() == self::STATUS_CREATED)
        {
            throw new \Exception('Error Occured StatusCode : '.$response->getStatusCode());
        }

        $contentJson = $response->getBody()->getContents();
        $contentObject = json_decode($contentJson);
        $token = $contentObject->Session->csrfToken;

        return $token;

    }

    /**
     * Create Draft
     *
     * @param $requestContent
     * @param $requestContentType
     * @param $accept
     * @param $token
     * @return array
     * @throws \Exception
     */
    public function createDraft($requestContent, $requestContentType, $accept, $token): array
    {
        //Internally we are working with json response (Accept)
        $headers =[
            'Content-Type'  => $requestContentType,
            'Accept'        => 'application/vnd.ez.api.Content+json',
            'X-CSRF-Token'  => $token
        ];

        $response = $this->restClient->sendHttpRequest(
            $this->restClient->createPostHeaderHttpRequest(self::CREATE_URL, $requestContent, $headers )
        );
        //$response = $this->restClient->sendRequest('/api/ezp/v2/content/objects', $requestContent, $headers );


        if(isset($response->getStatusCode) && !$response->getStatusCode() == self::STATUS_CREATED)
        {
            throw new \Exception('Error Occured StatusCode : '.$response->getStatusCode());
        }

        $contentJson = $response->getBody()->getContents();
        $contentObject = json_decode($contentJson);
        //HATEOS DRAFT HREF
        $draftHref = $contentObject->Content->CurrentVersion->Version->_href;
        $currentVersionHref = $contentObject->Content->CurrentVersion->_href;
        $contentId = $contentObject->Content->_id;

        return [
            'contentId' => $contentId,
            'draftHref' => $draftHref,
            'currentVersionHref' => $currentVersionHref
        ];
    }

    /**
     *  Content Publishing
     *
     * @param $draftHref
     * @param $token
     * @throws \Exception
     */
    public function publishContent($draftHref, $token): void
    {
        $headers =[
            'X-HTTP-Method-Override'    => 'Publish',
            'X-CSRF-Token'  => $token
        ];

        $response = $this->restClient->sendHttpRequest(
            $this->restClient->createPostHeaderHttpRequest($draftHref, false , $headers )
        );
        //$response = $this->restClient->sendRequest($draftHref, false , $headers );

        if(isset($response->getStatusCode) && !$response->getStatusCode() == self::STATUS_NO_CONTENT)
        {
            throw new \Exception('Error Occured StatusCode : '.$response->getStatusCode());
        }

    }

    /**
     * load Content at the end and return the result vary on Accept Header sent
     *
     * @param $currentVersionHref
     * @param $accept
     * @return null|string
     */
    public function loadContent($currentVersionHref, $accept): ? string
    {
        //deliver result vary on Accept Header
        $headers =[
            'Accept'        => $accept,
        ];

        $response = $this->restClient->sendHttpRequest(
            $this->restClient->createGetHeaderHttpRequest($currentVersionHref, false, $headers )
        );

        $content = $response->getBody()->getContents();

        return $content;

    }

}