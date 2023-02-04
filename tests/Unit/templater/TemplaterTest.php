<?php

use Flatness\Post;
use Flatness\PostList;
use Flatness\Templater;
use PHPUnit\Framework\TestCase;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class TemplaterTest extends TestCase
{
    public function testMake()
    {
        $templater = new Templater(__DIR__, ['global' => '123']);

        $html = $templater->make('make', ['local' => 456]);

        $this->assertStringContainsString('global 123', $html);
        $this->assertStringContainsString('local 456', $html);
    }

    public function testPagination()
    {
        $templater = new Templater(__DIR__, ['global' => '123']);

        $html = $templater->makePagination('pagination', 'post0', 1, 10);

        $this->assertStringContainsString('global 123', $html);
        $this->assertStringContainsString('uri post0', $html);
        $this->assertStringContainsString('currPage 1', $html);
        $this->assertStringContainsString('countPage 10', $html);
    }

    public function testMakeFromList()
    {
        $path = '/content/2022/01';
        $md = file_get_contents(__DIR__ . '/md.md');

        $postList = $this->createMock(PostList::class);
        $postList
            ->expects($this->exactly(3))
            ->method('next')
            ->will(
                $this->onConsecutiveCalls(
                    new Post(sprintf('%s/post0', $path), $md),
                    new Post(sprintf('%s/post1', $path), $md),
                    null
                )
            );

        $templater = new Templater(__DIR__);

        $html = $templater->makeFromList('list', $postList, 0);

        $this->assertStringContainsString('post post0', $html);
        $this->assertStringContainsString('post post1', $html);
    }
}
