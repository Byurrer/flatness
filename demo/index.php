<?php

require dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT_DIR', dirname(__DIR__));

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Flatness\Core\Services\Cache;
use Flatness\Core\Services\Content;
use Flatness\Core\Services\Templater;
use Flatness\Core\Services\PageFactory;
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
            '/{uri}.html',
            'Flatness\\Demo\\Controller::post'
        );
        $r->addRoute(
            'GET',
            '/tag/{tag}[/{page:\d+}]',
            'Flatness\\Demo\\Controller::tag'
        );
        $r->addRoute(
            'GET',
            '/category/{uri:.+}[/{page:\d+}]',
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

$env = [
    'buildUriPost' => fn(FileInterface $filePost) => sprintf("/%s.html", $filePost->getName()),
    'buildUriTag' => fn(string $tag) => sprintf("/tag/%s", $tag),
    'buildUriCategory' => fn(string $category) => sprintf("/category/%s", $category)
];

$cache = new Cache(ROOT_DIR . '/cache');
$content = new Content(ROOT_DIR . '/demo/content');
$templater = new Templater(ROOT_DIR . '/demo/Template', $env);
$pageFactory = new PageFactory(
    $content,
    $templater,
    $env['buildUriPost'],
    $env['buildUriTag'],
    $env['buildUriCategory'],
);
$resourceManager = new ResourceManager($pageFactory);

try {
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $response = new Response(404, [], $resourceManager->getService(404));
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $uriParams = $routeInfo[2];

            $request = new Request($httpMethod, $_SERVER['REQUEST_URI']);
            list($class, $method) = explode("::", $handler, 2);

            $ctl = new $class($resourceManager);

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
