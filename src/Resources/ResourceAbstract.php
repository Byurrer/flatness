<?php

namespace Flatness\Core\Resources;

use League\CommonMark\MarkdownConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterProviderInterface;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;

/**
 * Абстрактный ресурс, содержащий минимально необходимый набор данных
 */
abstract class ResourceAbstract
{
    public const TYPE_INDEX     = 'index';
    public const TYPE_CATEGORY  = 'category';
    public const TYPE_POST      = 'post';
    public const TYPE_TAG       = 'tag';
    public const TYPE_SERVICE   = 'service';

    //######################################################################

    final public function __construct()
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
        $this->converter = new MarkdownConverter($environment);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Получить ассоциативный массив со всем окружением объекта (для последующего extract)
     *
     * @return array<string, scalar>
     */
    public function getEnv(): array
    {
        $a = get_object_vars($this);
        unset($a['converter']);
        unset($a['render']);
        return $a;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected MarkdownConverter $converter;
    protected ?FrontMatterProviderInterface $render = null;

    protected string $uri = '';
    protected string $name = '';
    protected string $description = '';
    protected string $content = '';
    protected string $type = '';

    protected array $frontMatter = [];
}
