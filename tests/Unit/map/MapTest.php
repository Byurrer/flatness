<?php

use Flatness\Map;
use Flatness\Post;
use Flatness\Cache;
use Flatness\PostList;
use PHPUnit\Framework\TestCase;
use Flatness\FileSystemInterface;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class MapTest extends TestCase
{
    public function testGetPost()
    {
        $md = file_get_contents(__DIR__ . '/md.md');
        $fs = $this->createMock(FileSystemInterface::class);
        $fs->expects($this->exactly(1))->method('searchDir')->willReturn(__DIR__ . '2022/10/01/post0');
        $fs->expects($this->exactly(1))->method('loadFile')->willReturn($md);

        $cache = $this->createMock(Cache::class);

        $map = new Map($fs, $cache, __DIR__);

        $post = $map->getPost('post0');

        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame('post0', $post->getUri());
    }

    public function testGetTags()
    {
        $md = file_get_contents(__DIR__ . '/md.md');
        $mdtag1 = file_get_contents(__DIR__ . '/mdtag1.md');
        $mdtag2 = file_get_contents(__DIR__ . '/mdtag2.md');

        $fs = $this->createMock(FileSystemInterface::class);
        $fs->expects($this->exactly(1))->method('getDirs')->willReturn([
            '/2022/01/02/post0',
            '/2022/01/05/post1',
            '/2022/01/10/post2',
        ]);

        $fs->expects($this->exactly(3))->method('loadFile')->will(
            $this->onConsecutiveCalls(
                $md,
                $mdtag1,
                $mdtag2
            )
        );

        $cache = $this->createMock(Cache::class);
        $cache->expects($this->exactly(1))->method('get')->willReturn(null);
        $cache->expects($this->exactly(1))->method('save');

        $map = new Map($fs, $cache, __DIR__);

        $tags = $map->getTags();

        $this->assertIsArray($tags);
        $this->assertCount(3, $tags);

        $this->assertArrayHasKey('tag0', $tags);
        $this->assertArrayHasKey('tag1', $tags);
        $this->assertArrayHasKey('tag2', $tags);

        $this->assertEquals(
            [
                '/2022/01/02/post0',
                '/2022/01/05/post1'
            ],
            $tags['tag0']
        );

        $this->assertEquals(
            [
                '/2022/01/02/post0',
                '/2022/01/10/post2'
            ],
            $tags['tag1']
        );

        $this->assertEquals(
            [
                '/2022/01/02/post0',
                '/2022/01/05/post1',
                '/2022/01/10/post2'
            ],
            $tags['tag2']
        );
    }

    public function testGetPostsFull()
    {
        $fs = $this->createMock(FileSystemInterface::class);
        $fs->expects($this->exactly(1))->method('getDirs')->willReturn([
            '/2022/01/02/post0',
            '/2022/01/05/post1',
            '/2022/01/10/post2',
        ]);

        $cache = $this->createMock(Cache::class);

        $map = new Map($fs, $cache, __DIR__);

        $postList = $map->getPosts();

        $this->assertInstanceOf(PostList::class, $postList);
        $this->assertSame(3, $postList->count());
    }

    public function testGetPostTag()
    {
        $tags = [
            'tag0' => [
                '/2022/01/02/post0',
            ],
            'tag1' => [
                '/2022/01/02/post0',
                '/2022/01/10/post2'
            ],
            'tag2' => [
                '/2022/01/02/post0',
                '/2022/01/05/post1',
                '/2022/01/10/post2'
            ]
        ];

        $fs = $this->createMock(FileSystemInterface::class);

        $cache = $this->createMock(Cache::class);
        $cache->expects($this->exactly(3))->method('get')->willReturn($tags);

        $map = new Map($fs, $cache, __DIR__);

        $postList = $map->getPosts('tag0');
        $this->assertInstanceOf(PostList::class, $postList);
        $this->assertSame(1, $postList->count());

        $postList = $map->getPosts('tag1');
        $this->assertInstanceOf(PostList::class, $postList);
        $this->assertSame(2, $postList->count());

        $postList = $map->getPosts('tag2');
        $this->assertInstanceOf(PostList::class, $postList);
        $this->assertSame(3, $postList->count());
    }
}
