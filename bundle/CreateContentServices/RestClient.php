<?php

namespace Ez\RestDemoBundle\CreateContentServices;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as HttpRequest;

/**
 * Class RestClient
 *
 * @package Ez\RestDemoBundle\CreateContentServices
 */
class RestClient
{

    /** @var \GuzzleHttp\Client */
    private $guzzle;

    /** @var CookieJar*/
    private $cookieJar;

    /** @var string */
    private $httpHost;

    /**
     * RestClient constructor.
     *
     * @param \GuzzleHttp\Client $guzzle
     */
    public function __construct(Client $guzzle, CookieJar $cookieJar)
    {
        //Not used yet due
        //$this->guzzle = $guzzle;
        //Cookie are required

        $this->cookieJar =  $cookieJar;

        $this->guzzle = new \GuzzleHttp\Client([
            'base_uri' => $this->getHttpHost(),
            'cookies'  => $this->cookieJar
        ]);


    }

    /**
     * @param string $host
     * @return string
     */
    public function setHttpHost($host)
    {
        return $this->httpHost = $host;
    }

    /**
     * @return string
     */
    protected function getHttpHost()
    {
        return $this->httpHost;
    }

    /**
     * @param \GuzzleHttp\Psr7\Request $request
     * @return \Exception|\GuzzleHttp\Exception\RequestException|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function sendHttpRequest(HttpRequest $request)
    {
        try {
            $response = $this->guzzle->send($request);

        }catch (RequestException $e)
        {
            return $e;
        }

        return $response;

    }

    /**
     * @param $uri
     * @param $body
     * @param $headers
     * @return \GuzzleHttp\Psr7\Request
     */
    public function createPostHeaderHttpRequest($uri, $body, $headers)
    {
        $request = new HttpRequest('POST', $this->getHttpHost().$uri, $headers, $body);
        return $request;

    }

    /**
     * @param $uri
     * @param $body
     * @param $headers
     * @return \GuzzleHttp\Psr7\Request
     */
    public function createGetHeaderHttpRequest($uri, $body, $headers)
    {
        $request = new HttpRequest('GET', $this->getHttpHost().$uri, $headers, $body);
        return $request;

    }


    // Another possibilty to send post request
    /*
    public function sendRequest($uri, $body, $headers)
    {

        //$headers['Cookies'] = $this->cookieJar;

        try {
            $response = $this->guzzle->request('POST',
                $this->getHttpHost().$uri,
                array(
                    'body' => $body,
                    'headers' => $headers
                )
            );

        }catch (RequestException $e)
        {
            //$statusCode = $e->getCode(); //401 Unauthorized
            return $e;
        }

        return $response;

    }
    */


}