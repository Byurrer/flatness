<?php

namespace Flatness;

class PostList implements PostListInterface
{
    public function __construct(
        private string $contentDir,
        private FileSystemInterface $fs,
        private array $dirs,
        private $offset = 0
    ) {
    }

    public function next(): ?PostInterface
    {
        if (isset($this->dirs[$this->offset])) {
            $dir = $this->dirs[$this->offset++];
            $path = sprintf('%s/%s/index.md', $this->contentDir, $dir);
            $content = $this->fs->loadFile($path);
            return new Post($dir, $content);
        }
        return null;
    }

    public function offset(int $offset = null): int
    {
        if (is_int($offset)) {
            $this->offset = $offset;
        }

        return $this->offset;
    }

    public function count(): int
    {
        return count($this->dirs);
    }
}
