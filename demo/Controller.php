<?php

namespace Flatness\Demo;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Flatness\Core\Services\ResourceManagerInterface;

class Controller
{
    public function __construct(ResourceManagerInterface $resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }

    public function index(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $pageNum = (isset($uriParams['page']) ? $uriParams['page'] : 1);
        $page = $this->resourceManager->getIndex($pageNum);
        $response = new Response(200, [], $page);
        return $response;
    }

    public function category(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $uri = $uriParams['uri'];
        $pageNum = (isset($uriParams['page']) ? $uriParams['page'] : 1);
        $page = $this->resourceManager->getCategory($uri, $pageNum);
        $response = new Response(200, [], $page);
        return $response;
    }

    public function tag(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $tag = $uriParams['tag'];
        $pageNum = (isset($uriParams['page']) ? $uriParams['page'] : 1);
        $page = $this->resourceManager->getTag($tag, $pageNum);
        $response = new Response(200, [], $page);
        return $response;
    }

    public function post(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $uri = $uriParams['uri'];
        $page = $this->resourceManager->getPost($uri);
        $response = new Response(200, [], $page);
        return $response;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected ResourceManagerInterface $resourceManager;
}
