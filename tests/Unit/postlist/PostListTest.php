<?php

use Flatness\FileSystemInterface;
use Flatness\Post;
use Flatness\PostList;
use PHPUnit\Framework\TestCase;

define('CONTENT_DIR', __DIR__ . '/content');

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class PostListTest extends TestCase
{
    private string $md = '';
    private $fs;
    private array $dirs = [];

    public function setUp(): void
    {
        $this->md = file_get_contents(__DIR__ . '/md.md');
        $this->dirs = [
            '/content/2023/01/post4',
            '/content/2022/11/post3',
            '/content/2022/10/post2',
            '/content/2022/7/post1',
            '/content/2022/01/post0',
        ];

        $this->fs = $this->createMock(FileSystemInterface::class);
    }

    public function testWithoutOffset()
    {
        $this->fs->expects($this->exactly(5))->method('loadFile')->willReturn($this->md);

        $postList = new PostList(CONTENT_DIR, $this->fs, $this->dirs, 0);

        $this->assertSame($postList->count(), 5);
        $this->assertSame($postList->total(), 5);

        $i = 0;
        while ($post = $postList->next()) {
            $uri = basename($this->dirs[$i]);
            $this->assertSame($post->getUri(), $uri);
            ++$i;
        }

        $this->assertNull($postList->next());
    }

    public function testWithOffset()
    {
        $this->fs->expects($this->exactly(3))->method('loadFile')->willReturn($this->md);

        $postList = new PostList(CONTENT_DIR, $this->fs, $this->dirs, 2);

        $this->assertSame($postList->count(), 3);
        $this->assertSame($postList->total(), 5);

        $i = 2;
        while ($post = $postList->next()) {
            $uri = basename($this->dirs[$i]);
            $this->assertSame($post->getUri(), $uri);
            ++$i;
        }

        $this->assertNull($postList->next());


        $postList = new PostList(CONTENT_DIR, $this->fs, $this->dirs, 4);

        $this->assertSame($postList->count(), 1);
        $this->assertSame($postList->total(), 5);

        $postList->offset(6);

        $this->assertSame($postList->offset(), 5);
        $this->assertSame($postList->count(), 0);
        $this->assertSame($postList->total(), 5);
    }
}
