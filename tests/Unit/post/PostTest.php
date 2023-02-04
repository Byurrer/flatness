<?php

use Flatness\Post;
use PHPUnit\Framework\TestCase;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class PostTest extends TestCase
{
    public function test()
    {
        $meta = [
            'header' => 'Header',
            'description' => 'Description',
            'tags' => ['tag0', 'tag1', 'tag2']
        ];

        $path = '/content/2022/01/post0';
        $md = file_get_contents(__DIR__ . '/md.md');
        $post = new Post($path, $md);

        $this->assertSame('Header', $post->getHeader());
        $this->assertSame('Description', $post->getDescription());
        $this->assertSame('post0', $post->getUri());
        $this->assertSame($path, $post->getPath());
        $this->assertStringContainsString('<p>This is test post :)</p>', $post->getHtmlContent());
        $this->assertSame($meta['tags'], $post->getTags());

        $this->assertSame($meta, $post->getMeta());

        $this->assertEquals(
            [
                'header' => $post->getHeader(),
                'description' => $post->getDescription(),
                'uri' => $post->getUri(),
                'path' => $post->getPath(),
                'html' => $post->getHtmlContent(),
                'tags' => $post->getTags(),
                'meta' => $meta
            ],
            $post->toArray()
        );
    }
}
