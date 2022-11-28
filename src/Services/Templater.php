<?php

namespace Flatness\Core\Services;

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
    public function make(string $template, array $data = []): string
    {
        extract(array_merge($data, $this->env));
        ob_start();
        include(sprintf('%s/%s.php', $this->pathTemplateDir, $template));
        $html = ob_get_clean();

        return $html;
    }

    /**
     * @inheritDoc
     */
    public function makePagination(string $template, string $uri, int $currPage, int $countPage): string
    {
        extract($this->env);
        ob_start();
        include(sprintf('%s/%s.php', $this->pathTemplateDir, $template));
        $html = ob_get_clean();

        return $html;
    }

    /**
     * @inheritDoc
     */
    public function makeFromContainer(string $template, ContainerAbstract $resources): string
    {
        $a = [];
        foreach ($resources as $resource) {
            $a[] = $this->make($template, $resource->getEnv());
        }

        return implode('', $a);
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected $pathTemplateDir = '';
    protected $env = [];
}
