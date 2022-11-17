<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Tag;
use Flatness\Core\Resources\Page;
use Flatness\Core\Resources\Post;
use Flatness\Core\Resources\Index;
use Flatness\Core\Resources\Category;

/**
 * Реализация фабрики страниц
 */
class PageFactory implements PageFactoryInterface
{
    public function __construct(
        ContentInterface $content,
        TemplaterInterface $templater,
        callable $buildUriPost,
        callable $buildUriTag,
        callable $buildUriCategory
    ) {
        $this->content = $content;
        $this->templater = $templater;
        $this->buildUriPost = $buildUriPost;
        $this->buildUriTag = $buildUriTag;
        $this->buildUriCategory = $buildUriCategory;
    }

    /**
     * @inheritDoc
     */
    public function makeIndex(int $pagenum = 1): Page
    {
        $index = $this->content->getDirectory('/');
        $postList = Index::fromDirectory(
            $index,
            '/',
            $this->buildUriPost,
            ($pagenum - 1) * PAGINATION_LIMIT,
            PAGINATION_LIMIT
        );
        $page = $this->templater->makePage($postList);

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function makeCategory(string $path, int $pagenum = 1): Page
    {
        if (!($category = $this->content->getDirectory($path))) {
            return $this->templater->makeService(404);
        }
        $buildUriCategory = $this->buildUriCategory;
        $uri = $buildUriCategory($category->getName());
        $postList = Category::fromDirectory(
            $category,
            $uri,
            $this->buildUriPost,
            ($pagenum - 1) * PAGINATION_LIMIT,
            PAGINATION_LIMIT
        );
        $page = $this->templater->makePage($postList);

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function makeTag(string $tag, int $pagenum = 1): Page
    {
        $offset = ($pagenum - 1) * PAGINATION_LIMIT;
        $directory = $this->content->getDirectory('/');
        $postListAll = Index::fromDirectory($directory, '', $this->buildUriPost, 0, PHP_INT_MAX);
        $postListConcrete = new Tag();

        $total = 0;
        for ($i = 0; $i < $postListAll->count(); ++$i) {
            $post = $postListAll[$i];
            if (array_search($tag, $post->getTags()) !== false) {
                ++$total;
                if ($total > $offset && $postListConcrete->count() < PAGINATION_LIMIT) {
                    $postListConcrete[] = $post;
                }
            }
        }

        if ($postListConcrete->count() == 0) {
            $page = $this->templater->makeService(404);
        } else {
            $postListConcrete->setOffset($offset);
            $postListConcrete->setLimit(PAGINATION_LIMIT);
            $postListConcrete->setTotal($total);

            $buildUriTag = $this->buildUriTag;
            $postListConcrete->setUri($buildUriTag($tag));
            $postListConcrete->setName($tag);
            $postListConcrete->setDescription('');

            $page = $this->templater->makePage($postListConcrete);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function makePost(string $name): Page
    {
        if (!($postFile = $this->content->getFile($name))) {
            return $this->templater->makeService(404);
        }
        $buildUriPost = $this->buildUriPost;
        $post = Post::fromFile($postFile, $buildUriPost($postFile));
        $page = $this->templater->makePage($post);

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function makeService(int $code): Page
    {
        return $this->templater->makeService($code);
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected ContentInterface $content;
    protected Templater $templater;
    protected $buildUriPost;
    protected $buildUriTag;
    protected $buildUriCategory;
}
