<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Post;
use Flatness\Core\Resources\ResourceContainer;

/**
 * Реализация менеджера ресурсов
 */
class ResourceManager implements ResourceManagerInterface
{
    public function __construct(
        FileManagerInterface $fileManager,
        callable $buildUriPost,
        callable $buildUriTag,
        callable $buildUriCategory,
        int $perPage = 10
    ) {
        $this->fileManager = $fileManager;
        $this->perPage = $perPage;

        $this->buildUriPost = $buildUriPost;
        $this->buildUriTag = $buildUriTag;
        $this->buildUriCategory = $buildUriCategory;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(int $pagenum = 1): ?ResourceContainer
    {
        $index = $this->fileManager->getDirectory('/');
        $postList = ResourceContainer::fromDirectory(
            $index,
            '/',
            $this->buildUriPost,
            ($pagenum - 1) * $this->perPage,
            $this->perPage
        );

        return ($postList->count() > 0 ? $postList : null);
    }

    /**
     * @inheritDoc
     */
    public function getCategory(string $path, int $pagenum = 1): ?ResourceContainer
    {
        if (!($category = $this->fileManager->getDirectory($path))) {
            return null;
        }

        $postList = ResourceContainer::fromDirectory(
            $category,
            ($this->buildUriCategory)($category->getName()),
            $this->buildUriPost,
            ($pagenum - 1) * $this->perPage,
            $this->perPage
        );

        return ($postList->count() > 0 ? $postList : null);
    }

    /**
     * @inheritDoc
     */
    public function getTag(string $name, int $pagenum = 1): ?ResourceContainer
    {
        $offset = ($pagenum - 1) * $this->perPage;
        $directory = $this->fileManager->getDirectory('/');
        $postListAll = ResourceContainer::fromDirectory($directory, '', $this->buildUriPost, 0, PHP_INT_MAX);
        $postListConcrete = new ResourceContainer();

        $total = 0;
        for ($i = 0; $i < $postListAll->count(); ++$i) {
            $post = $postListAll[$i];
            if (array_search($name, $post->getTags()) !== false) {
                ++$total;
                if ($total > $offset && $postListConcrete->count() < $this->perPage) {
                    $postListConcrete[] = $post;
                }
            }
        }

        if ($postListConcrete->count() == 0) {
            return null;
        } else {
            $postListConcrete->setOffset($offset);
            $postListConcrete->setLimit($this->perPage);
            $postListConcrete->setTotal($total);
            $postListConcrete->setUri(($this->buildUriTag)($name));
            $postListConcrete->setName($name);
            $postListConcrete->setDescription('');
        }

        return $postListConcrete;
    }

    /**
     * @inheritDoc
     */
    public function getPost(string $name): ?Post
    {
        if (!($postFile = $this->fileManager->getFile($name))) {
            return null;
        }

        $post = Post::fromFile($postFile, ($this->buildUriPost)($postFile->getName()));

        return $post;
    }

    /**
     * @inheritDoc
     */
    public function getCategories(): array
    {
        if ($this->cachedTags) {
            return $this->cachedTags;
        }

        $root = $this->fileManager->getDirectory('/');
        $iterator = $root->getDirectoryIterator();

        $a = [];
        while ($dir = $iterator->current()) {
            $category = ResourceContainer::fromDirectory($dir, '', $this->buildUriPost);
            $tmp = Post::fromFile($dir->getIndex(), ($this->buildUriCategory)($dir->getName()))->getEnv();
            $a[$tmp['name']] = [
                'name' => $tmp['name'],
                'uri' => $tmp['uri'],
                'frontMatter' => $tmp['frontMatter'],
                'count' => $category->getTotal(),
            ];
        }

        $this->cachedTags = $a;

        return $a;
    }

    /**
     * @inheritDoc
     */
    public function getTags(): array
    {
        if ($this->cachedCategories) {
            return $this->cachedCategories;
        }

        $root = $this->fileManager->getDirectory('/');
        $postList = ResourceContainer::fromDirectory(
            $root,
            '/',
            $this->buildUriPost,
            0,
            PHP_INT_MAX
        );

        $a = [];
        for ($i = 0; $i < $postList->count(); ++$i) {
            $post = $postList[$i];
            $tags = $post->getTags();
            foreach ($tags as $tag) {
                if (!isset($a[$tag])) {
                    $a[$tag] = [
                        'name' => $tag,
                        'uri' => ($this->buildUriTag)($tag),
                        'count' => 0,
                    ];
                }
                $a[$tag]['count'] += 1;
            }
        }

        $this->cachedCategories = $a;

        return $a;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected FileManagerInterface $fileManager;
    protected int $perPage = 10;

    protected $buildUriPost;
    protected $buildUriTag;
    protected $buildUriCategory;

    protected $cachedCategories = [];
    protected $cachedTags = [];
}
