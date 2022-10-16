<?php

namespace Flatness\Core\Resources;

use Flatness\Core\FileSystem\FileInterface;
use League\CommonMark\Extension\FrontMatter\FrontMatterProviderInterface;

/**
 * Пост
 */
class Post extends ResourceAbstract implements RenderableInterface
{
    public static function fromFile(FileInterface $file): self
    {
        $post = new self();
        $post->setUri($file->getName());

        $md = $file->getContent();

        /** @var FrontMatterProviderInterface */
        $post->render = $post->converter->convert($md);
        $map = $post->render->getFrontMatter();

        $name = $map['name'];
        $description = $map['description'];
        $tags = $map['tags'];
        $categories = $file->getParents();

        $post->setName($name);
        $post->setDescription($description);
        $post->setCategories($categories);
        $post->setTags($tags);
        $post->setContent($md);

        $post->frontMatter = $map;

        return $post;
    }

    public function getHtmlContent(): string
    {
        if (!$this->render) {
            $this->render = $this->converter->convert($this->content);
        }
        return $this->render->getContent();
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function toStringTags(): string
    {
        return implode(', ', $this->tags);
    }

    /**
     * @inheritDoc
     */
    public function getEnv(): array
    {
        $a = parent::getEnv();
        $a['content'] = $this->getHtmlContent();
        return $a;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected array $category = [];

    protected string $type = Page::TYPE_POST;

    /** @var array<string> */
    protected array $tags = [];
}
