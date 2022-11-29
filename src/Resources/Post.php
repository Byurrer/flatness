<?php

namespace Flatness\Core\Resources;

use Flatness\Core\FileSystem\FileInterface;
use League\CommonMark\Extension\FrontMatter\FrontMatterProviderInterface;

/**
 * Пост
 */
class Post extends ResourceAbstract implements RenderableInterface
{
    /**
     * Создать объект из файла
     *
     * @param FileInterface $file
     * @param string $uri
     * @return self
     */
    public static function fromFile(FileInterface $file, string $uri): self
    {
        $post = new self();
        $post->setUri($uri);

        $md = $file->getContent();

        /** @var FrontMatterProviderInterface */
        $post->render = $post->converter->convert($md);
        $map = $post->render->getFrontMatter();

        $name = $map['name'];
        $description = $map['description'];
        $tags = (isset($map['tags']) ? $map['tags'] : []);
        $categories = $file->getParents();

        $post->setName($name);
        $post->setDescription($description);
        $post->categories = $categories;
        $post->tags = $tags;
        $post->setContent($md);

        $post->frontMatter = $map;

        return $post;
    }

    /**
     * @inheritDoc
     */
    public function getHtmlContent(): string
    {
        if (!$this->render) {
            $this->render = $this->converter->convert($this->content);
        }
        return $this->render->getContent();
    }

    /**
     * Получить массив категорий поста
     *
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Получить массив тегов поста
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
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

    /** @var array<string> */
    protected array $categories = [];

    /** @var array<string> */
    protected array $tags = [];
}
