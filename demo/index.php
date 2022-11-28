<?php

require dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT_DIR', dirname(__DIR__));

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Flatness\Core\Services\Cache;
use Flatness\Core\Services\Templater;
use Flatness\Core\Services\FileManager;
use Flatness\Core\FileSystem\FileInterface;
use Flatness\Core\Services\ResourceManager;

//##########################################################################

$dispatcher = FastRoute\simpleDispatcher(
    function (FastRoute\RouteCollector $r) {
        $r->addRoute(
            'GET',
            '/',
            'Flatness\\Demo\\Controller::index'
        );
        $r->addRoute(
            'GET',
            '/{page:\d+}',
            'Flatness\\Demo\\Controller::index'
        );
        $r->addRoute(
            'GET',
            '/{post}.html',
            'Flatness\\Demo\\Controller::post'
        );
        $r->addRoute(
            'GET',
            '/tag/{tag}[/{page:\d+}]',
            'Flatness\\Demo\\Controller::tag'
        );
        $r->addRoute(
            'GET',
            '/category/{category}[/{page:\d+}]',
            'Flatness\\Demo\\Controller::category'
        );
    }
);

//##########################################################################

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strval(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$uri = rawurldecode($uri);

/** @var Response */
$response = null;

$buildUriPost = fn(string $post) => sprintf("/%s.html", $post);
$buildUriTag = fn(string $tag) => sprintf("/tag/%s", $tag);
$buildUriCategory = fn(string $category) => sprintf("/category/%s", $category);


$cache = new Cache(ROOT_DIR . '/cache');
$fileManager = new FileManager(ROOT_DIR . '/demo/content');
$resourceManager = new ResourceManager(
    $fileManager,
    $buildUriPost,
    $buildUriTag,
    $buildUriCategory,
);

$cache->clear();

$tags = [];
$cats = [];

if (!($tags = $cache->getData('tags'))) {
    $tags = $resourceManager->getTags();
    $cache->saveData('tags', $tags);
}

if (!($cats = $cache->getData('cats'))) {
    $cats = $resourceManager->getCategories();
    $cache->saveData('cats', $cats);
}

$env = [
    'buildUriPost' => $buildUriPost,
    'buildUriTag' => $buildUriTag,
    'buildUriCategory' => $buildUriCategory,
    'allTags' => $tags,
    'allCategories' => $cats,
];

$templater = new Templater(ROOT_DIR . '/demo/Template', $env);


try {
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $response = new Response(404, [], $templater->make('service', ['code' => 404]));
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $uriParams = $routeInfo[2];

            $request = new Request($httpMethod, $_SERVER['REQUEST_URI']);
            list($class, $method) = explode("::", $handler, 2);

            $ctl = new $class(
                $resourceManager,
                $cache,
                $templater
            );

            /** @var Response */
            $response = $ctl->$method($request, $uriParams, $_GET);
            break;
        default:
            $response = null;
            break;
    }
} catch (\Exception $e) {
    error_log($e->__toString(), 0);
}

//##########################################################################

if ($response !== null) {
    http_response_code($response->getStatusCode());
    $headers = $response->getHeaders();
    foreach ($headers as $name => $value) {
        if ($value) {
            header(sprintf('%s: %s', $name, array_shift($value)));
        }
    }
    $responseBody = $response->getBody()->getContents();
    echo $responseBody;
    $response->getBody()->rewind();
} else {
    http_response_code(500);
}
