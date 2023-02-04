<?php

namespace Flatness;

/**
 * Реализация шаблонизатора
 */
class Templater implements TemplaterInterface
{
    /**
     * @param string $pathTemplateDir
     * @param array<string, mixed> $env дополнительные данные передаваемые в каждый шаблон
     */
    public function __construct(
        private string $pathTemplateDir,
        private array $env = []
    ) {
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
    public function makeFromList(string $template, PostList $postList, int $limit = 0): string
    {
        $a = [];
        $i = 0;
        while ($post = $postList->next()) {
            if ($limit > 0 && $i++ >= $limit) {
                break;
            }
            $a[] = $this->make($template, $post->toArray());
        }

        return implode('', $a);
    }
}
