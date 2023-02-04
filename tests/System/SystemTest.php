<?php

use Flatness\Map;
use Flatness\Post;
use Flatness\Cache;
use Flatness\PostList;
use Flatness\FileSystem;
use PHPUnit\Framework\TestCase;
use Flatness\FileSystemInterface;

define('SYSTEM_CONTENT_DIR', __DIR__ . '/content');
define('SYSTEM_CACHE_DIR', __DIR__ . '/cache');

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class SystemTest extends TestCase
{
    private FileSystemInterface $fs;
    private Cache $cache;
    private Map $map;

    protected function setUp(): void
    {
        $this->fs = new FileSystem();
        $this->cache = new Cache($this->fs, SYSTEM_CACHE_DIR);
        $this->map = new Map($this->fs, $this->cache, SYSTEM_CONTENT_DIR);
    }

    public static function tearDownAfterClass(): void
    {
        $fs = new FileSystem();
        $fs->rmdir(SYSTEM_CACHE_DIR);
    }

    //######################################################################

    public function testTags()
    {
        $tags = $this->map->getTags();

        $this->assertIsArray($tags);
        $this->assertCount(4, $tags);
    }

    public function testGetPost()
    {
        $post = $this->map->getPost('first-news');
        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame('first-news', $post->getUri());

        $post = $this->map->getPost('new-year');
        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame('new-year', $post->getUri());
    }

    public function testGetPostsFull()
    {
        $posts = $this->map->getPosts();

        $this->assertInstanceOf(PostList::class, $posts);
        $this->assertSame(5, $posts->count());
    }

    public function testGetPostsTag()
    {
        $posts = $this->map->getPosts('blog');
        $this->assertInstanceOf(PostList::class, $posts);
        $this->assertSame(3, $posts->count());

        $posts = $this->map->getPosts('news');
        $this->assertInstanceOf(PostList::class, $posts);
        $this->assertSame(2, $posts->count());

        $posts = $this->map->getPosts('snow');
        $this->assertInstanceOf(PostList::class, $posts);
        $this->assertSame(2, $posts->count());

        $posts = $this->map->getPosts('test');
        $this->assertInstanceOf(PostList::class, $posts);
        $this->assertSame(1, $posts->count());
    }
}
