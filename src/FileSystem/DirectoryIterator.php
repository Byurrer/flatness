<?php

namespace Flatness\Core\FileSystem;

/**
 * Итератор по директориям
 */
class DirectoryIterator extends IteratorAbstract
{
    public function current(): ?DirectoryInterface
    {
        if ($this->current < count($this->objects)) {
            $path = $this->objects[$this->current++];
            return new Directory($path, $this->rootPath);
        }

        $this->current = 0;
        return null;
    }
}
