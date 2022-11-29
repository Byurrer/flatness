<?php

namespace Flatness\Core\FileSystem;

/**
 * Итератор по файлам
 */
class FileIterator extends IteratorAbstract
{
    /**
     * @inheritDoc
     */
    public function current(): ?FileInterface
    {
        if ($this->current < count($this->objects)) {
            $path = $this->objects[$this->current++];
            return new File($path, $this->rootPath);
        }

        $this->current = 0;
        return null;
    }
}
