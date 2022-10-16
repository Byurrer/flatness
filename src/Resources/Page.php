<?php

namespace Flatness\Core\Resources;

/**
 * Страница
 */
class Page
{
    public const TYPE_INDEX     = 'index';
    public const TYPE_CATEGORY  = 'category';
    public const TYPE_POST      = 'post';
    public const TYPE_TAG       = 'tag';
    public const TYPE_SERVICE   = 'service';

    //######################################################################

    public function __construct()
    {
    }

    public static function fromArray(array $a): self
    {
        $page = new static();
        $page->setType($a['type']);
        $page->setUri($a['uri']);
        $page->setContent($a['content']);
        $page->setPagenum($a['pagenum']);
        return $page;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
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

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    //**********************************************************************

    public function setPagenum(int $pagenum): self
    {
        $this->pagenum = $pagenum;
        return $this;
    }

    public function getPagenum(): ?int
    {
        return $this->pagenum;
    }

    //**********************************************************************

    public function __toString(): string
    {
        return $this->content;
    }

    public function asArray(): array
    {
        return [
            'type'      => $this->type,
            'uri'       => $this->uri,
            'content'   => $this->content,
            'pagenum'   => $this->pagenum,
        ];
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected string $type = '';
    protected string $uri = '';
    protected string $content = '';
    protected ?int $pagenum = null;
}
