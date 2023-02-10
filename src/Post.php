<?php

namespace Flatness;

use League\CommonMark\MarkdownConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Output\RenderedContentInterface;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterProviderInterface;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;

class Post implements PostInterface
{
    public function __construct(string $path, string $md)
    {
        $config = [
            'table_of_contents' => [
                'html_class' => 'toc',
                'position' => 'top',
                'style' => 'bullet',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'normalize' => 'relative',
                'placeholder' => null,
            ],
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => '',
                'fragment_prefix' => '',
                'insert' => 'before',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'title' => 'Permalink',
                'symbol' => '#',
                'aria_hidden' => true,
            ],
            'external_link' => [
                'open_in_new_window' => true,
                'html_class' => 'external-link',
            ],
        ];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new ExternalLinkExtension());
        $environment->addExtension(new FrontMatterExtension());
        $environment->addExtension(new HeadingPermalinkExtension());
        $environment->addExtension(new TableOfContentsExtension());
        $environment->addExtension(new AttributesExtension());
        $environment->addExtension(new StrikethroughExtension());
        $converter = new MarkdownConverter($environment);

        $md = static::complementationImagesUrl($md, $path);

        /** @var FrontMatterProviderInterface & RenderedContentInterface */
        $render = $converter->convert($md);

        $this->meta = $render->getFrontMatter();

        $this->header = $this->meta['header'];
        $this->description = $this->meta['description'];
        $this->path = $path;
        $this->uri = basename($path);
        $this->html = $render->getContent();
        $this->tags = $this->meta['tags'];
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHtmlContent(): string
    {
        return $this->html;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function toArray(): array
    {
        $a = get_object_vars($this);
        return $a;
    }

    //######################################################################

    public static function complementationImagesUrl(string $md, string $relPath): string
    {
        $md = preg_replace_callback(
            '/\[(.*?)\]\((.*?)\)/',
            function ($matches) use ($relPath) {
                $isAbs = ($matches[2][0] == '/' || parse_url($matches[2], PHP_URL_SCHEME));
                return sprintf(
                    '[%s](%s)',
                    $matches[1],
                    ($isAbs ? $matches[2] : rtrim($relPath, '/') . '/' . $matches[2])
                );
            },
            $md
        );

        return $md;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected string $header = '';
    protected string $description = '';
    protected string $uri = '';
    protected string $html = '';
    protected string $path = '';
    protected array $tags = [];
    protected array $meta = [];
}
