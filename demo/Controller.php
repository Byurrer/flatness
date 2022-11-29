<?php

namespace Flatness\Demo;

use Flatness\Core\Services\CacheInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Flatness\Core\Services\ResourceManagerInterface;
use Flatness\Core\Services\TemplaterInterface;

class Controller
{
    public function __construct(
        ResourceManagerInterface $resourceManager,
        ?CacheInterface $cache,
        TemplaterInterface $templater
    ) {
        $this->resourceManager = $resourceManager;
        $this->cache = $cache;
        $this->templater = $templater;
    }

    public function index(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $pageNum = (isset($uriParams['page']) ? $uriParams['page'] : 1);

        $code = 200;
        $cachedPath = sprintf('index-%s', $pageNum);
        $page = null;
        if (!$this->cache || !($page = $this->cache->getPage($cachedPath))) {
            if ($resource = $this->resourceManager->getIndex($pageNum)) {
                $countPage = ceil($resource->getTotal() / $resource->getLimit());
                $currPage = round($resource->getOffset() / $resource->getLimit() + 0.5);

                $uri = $request->getRequestTarget();
                $uri = $this->extrudeUriPagination($uri);

                $env = $resource->getEnv();
                $env['content'] = $this->templater->makeFromContainer('card', $resource);
                $env['content'] .= $this->templater->makePagination('pagination', $uri, $currPage, $countPage);
                $env['type'] = 'index';

                $page = $this->templater->make('index', $env);
                $this->cache->savePage($cachedPath, $page);
            } else {
                $this->templater->make('service', ['code' => 404]);
            }
        }

        $response = new Response($code, [], $page);
        return $response;
    }

    public function tags(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $page = null;
        if (!($page = $this->cache->getPage('tags'))) {
            if (!($tags = $this->cache->getData('tags'))) {
                $tags = $this->resourceManager->getTags();
            }
            $page = $this->templater->make('tags', $tags);
            $this->cache->savePage('tags', $page);
        }

        $response = new Response(200, [], $page);
        return $response;
    }

    public function categories(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $page = null;
        if (!($page = $this->cache->getPage('categories'))) {
            if (!($categoties = $this->cache->getData('categories'))) {
                $categoties = $this->resourceManager->getCategories();
            }
            $page = $this->templater->make('categories', $categoties);
            $this->cache->savePage('categories', $page);
        }

        $response = new Response(200, [], $page);
        return $response;
    }

    public function category(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $category = $uriParams['category'];
        $pageNum = intval(isset($uriParams['page']) ? $uriParams['page'] : 1);

        $code = 200;
        $cachedPath = sprintf('%s-%d', $category, $pageNum);
        $page = null;
        if (!($page = $this->cache->getPage($cachedPath))) {
            if ($resource = $this->resourceManager->getCategory($category, $pageNum)) {
                $countPage = ceil($resource->getTotal() / $resource->getLimit());
                $currPage = round($resource->getOffset() / $resource->getLimit() + 0.5);

                $uri = $request->getRequestTarget();
                $uri = $this->extrudeUriPagination($uri);

                $env = $resource->getEnv();
                $env['content'] = $this->templater->makeFromContainer('card', $resource);
                $env['content'] .= $this->templater->makePagination('pagination', $uri, $currPage, $countPage);
                $env['type'] = 'category';

                $page = $this->templater->make('index', $env);
                $this->cache->savePage($cachedPath, $page);
            } else {
                $this->templater->make('service', ['code' => 404]);
            }
        }

        $response = new Response($code, [], $page);
        return $response;
    }

    public function tag(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $tag = $uriParams['tag'];
        $pageNum = (isset($uriParams['page']) ? $uriParams['page'] : 1);

        $code = 200;
        $cachedPath = sprintf('%s-%d', $tag, $pageNum);
        $page = null;
        if (!($page = $this->cache->getPage($cachedPath))) {
            if ($resource = $this->resourceManager->getTag($tag, $pageNum)) {
                $countPage = ceil($resource->getTotal() / $resource->getLimit());
                $currPage = round($resource->getOffset() / $resource->getLimit() + 0.5);

                $uri = $request->getRequestTarget();
                $uri = $this->extrudeUriPagination($uri);

                $env = $resource->getEnv();
                $env['content'] = $this->templater->makeFromContainer('card', $resource);
                $env['content'] .= $this->templater->makePagination('pagination', $uri, $currPage, $countPage);
                $env['type'] = 'tag';

                $page = $this->templater->make('index', $env);
                $this->cache->savePage($cachedPath, $page);
            } else {
                $this->templater->make('service', ['code' => 404]);
            }
        }

        $response = new Response($code, [], $page);
        return $response;
    }

    public function post(RequestInterface $request, array $uriParams, array $getParams): ResponseInterface
    {
        $post = $uriParams['post'];

        $code = 200;
        $page = null;
        if (!$this->cache || !($page = $this->cache->getPage($post))) {
            if ($resource = $this->resourceManager->getPost($post)) {
                $env = $resource->getEnv();
                $env['type'] = 'post';
                $page = $this->templater->make('index', $env);
                $this->cache->savePage($post, $page);
            } else {
                $this->templater->make('service', ['code' => 404]);
            }
        }

        $response = new Response($code, [], $page);
        return $response;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected ResourceManagerInterface $resourceManager;
    protected ?CacheInterface $cache;
    protected TemplaterInterface $templater;

    //######################################################################

    protected function extrudeUriPagination(string $uri): string
    {
        $a = explode('/', $uri);
        if (is_numeric($a[count($a) - 1])) {
            array_pop($a);
            $uri = implode('/', $a);
        }

        $uri = rtrim($uri, '/') . '/';

        return $uri;
    }
}
