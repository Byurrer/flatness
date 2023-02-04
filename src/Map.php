<?php

namespace Flatness;

class Map implements MapInterface
{
    public function __construct(
        private FileSystemInterface $fs,
        private CacheInterface $cache,
        private string $dirContent
    ) {
    }

    public function getPosts(string $tag = null): PostListInterface
    {
        $dirs = [];
        if ($tag) {
            $tags = $this->getTags();
            $dirs = $tags[$tag];
        } else {
            $dirs = $this->fs->getDirs($this->dirContent);
        }

        return new PostList($this->dirContent, $this->fs, $dirs);
    }

    public function getPost(string $uri): ?PostInterface
    {
        if ($path = $this->fs->searchDir($this->dirContent, $uri)) {
            return new Post($path, $this->fs->loadFile(sprintf('%s/%s/index.md', $this->dirContent, $path)));
        }

        return null;
    }

    public function getTags(): array
    {
        if (!($tags = $this->cache->get('tags'))) {
            $tags = $this->buildTags();
            $this->cache->save('tags', $tags);
        }
        return $tags;
    }

    //######################################################################
    // PRIVATE
    //######################################################################

    private function buildTags(): array
    {
        $dirs = $this->fs->getDirs($this->dirContent);
        $postList = new PostList($this->dirContent, $this->fs, $dirs);

        $tags = [];

        while ($post = $postList->next()) {
            foreach ($post->getTags() as $tag) {
                $tags[$tag][] = $post->getPath();
            }
        }

        return $tags;
    }
}
