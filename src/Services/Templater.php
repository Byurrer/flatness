<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Page;
use Flatness\Core\Resources\Post;
use Flatness\Core\Resources\ResourceAbstract;
use Flatness\Core\Resources\ContainerAbstract;

/**
 * Реализация шаблонизатора
 */
class Templater implements TemplaterInterface
{
    /**
     * @param string $pathTemplateDir
     * @param array<string, mixed> $env дополнительные данные передаваемые в каждый шаблон
     */
    public function __construct(string $pathTemplateDir, array $env = [])
    {
        $this->pathTemplateDir = $pathTemplateDir;
        $this->env = $env;
    }

    /**
     * @inheritDoc
     */
    public function makePage(ResourceAbstract $resource): Page
    {
        $env = array_merge($resource->getEnv(), $this->env);

        if (is_subclass_of($resource, ContainerAbstract::class)) {
            $env['content'] = $this->getArrayContent($resource);

            /** @var ContainerAbstract */
            $container = $resource;
            $countPage = ceil($container->getTotal() / $container->getLimit());
            $currPage = round($container->getOffset() / $container->getLimit() + 0.5);
            $env['content'] .= $this->makePagination($resource, $currPage, $countPage);
        } elseif (is_subclass_of($resource, Post::class) || get_class($resource) == Post::class) {
            extract($env);
            ob_start();
            include($this->pathTemplateDir . '/Post.php');
            $env['content'] = ob_get_clean();
        }

        extract($env);

        ob_start();
        include($this->pathTemplateDir . '/Index.php');
        $html = ob_get_clean();

        $page = new Page();
        $page->setContent($html);
        $page->setUri($resource->getUri());
        $page->setType($resource->getType());

        if (is_subclass_of($resource, ContainerAbstract::class)) {
            $page->setPagenum($currPage);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function makeCard(ResourceAbstract $resource): string
    {
        $env = array_merge($resource->getEnv(), $this->env);
        extract($env);

        ob_start();
        include($this->pathTemplateDir . '/Card.php');
        $html = ob_get_clean();

        return $html;
    }

    /**
     * @inheritDoc
     */
    public function makePagination(ContainerAbstract $resource, int $currPage, int $countPage): string
    {
        $uri = $resource->getUri();
        if ($uri != '/') {
            $uri = '/' . $uri . '/';
        }
        extract($this->env);
        ob_start();
        include($this->pathTemplateDir . '/Pagination.php');
        $html = ob_get_clean();

        return $html;
    }

    //######################################################################

    /**
     * @inheritDoc
     */
    public function makeService(int $code): Page
    {
        $name = $content = strval($code);
        $type = Page::TYPE_SERVICE;
        extract($this->env);
        ob_start();
        include($this->pathTemplateDir . '/Index.php');
        $html = ob_get_clean();

        $page = new Page();
        $page->setContent($html);
        $page->setUri('');
        $page->setType(Page::TYPE_SERVICE);

        return $page;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected $pathTemplateDir = '';
    protected $env = [];

    //######################################################################

    /**
     * @param ContainerAbstract<ResourceAbstract>$resources
     * @return string
     */
    protected function getArrayContent(ContainerAbstract $resources): string
    {
        extract($this->env);
        $a = [];
        foreach ($resources as $resource) {
            $a[] = $this->makeCard($resource);
        }

        return implode('', $a);
    }
}
