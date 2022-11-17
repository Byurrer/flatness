<?php

namespace Flatness\Core\Resources;

use Flatness\Core\FileSystem\DirectoryInterface;

/**
 * Контейнер ресурсов
 */
abstract class ContainerAbstract extends ResourceAbstract implements \ArrayAccess, \Countable, \Iterator
{
    public static function fromDirectory(
        DirectoryInterface $category,
        string $uri,
        callable $postUriBuilder,
        int $offset = 0,
        int $limit = 10
    ): self {
        $postList = new static();
        $postList->setOffset($offset);
        $postList->setLimit($limit);
        $category->setOffset($offset);
        $postList->setTotal($category->getTotal());

        $i = 0;
        while (
            $i++ < $limit
            && ($postFile = $category->getFileIncr())
        ) {
            $postList[] = Post::fromFile($postFile, $postUriBuilder($postFile));
        }

        $indexFile = $category->getIndex();
        $md = $indexFile->getContent();
        $postList->render = $postList->converter->convert($md);
        $map = $postList->render->getFrontMatter();

        $postList->setUri($uri);
        $postList->setName($map['name']);
        $postList->setDescription($map['description']);
        $postList->frontMatter = $map;

        return $postList;
    }

    //######################################################################

    /**
     * @inheritDoc
     */
    public function getEnv(): array
    {
        $a = parent::getEnv();
        unset($a['objects']);
        return $a;
    }

    //######################################################################
    // Pagination

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    //######################################################################
    // ArrayAccess

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->objects[] = $value;
        } else {
            $this->objects[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->objects[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->objects[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->objects[$offset]) ? $this->objects[$offset] : null;
    }


    //######################################################################
    // Iterator

    public function current(): ?ResourceAbstract
    {
        $res = current($this->objects);
        return ($res ? $res : null);
    }

    /**
     * @return string|int|null
     */
    public function key()
    {
        return key($this->objects);
    }

    public function next(): void
    {
        next($this->objects);
    }

    public function rewind(): void
    {
        reset($this->objects);
    }

    public function valid(): bool
    {
        return isset($this->objects[$this->key()]);
    }

    //######################################################################
    // Countable

    public function count(): int
    {
        return count($this->objects);
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    /**
     * @var array<ResourceAbstract>
     */
    protected array $objects = [];

    protected int $offset = 0;
    protected int $limit = 10;
    protected int $total = 0;
}
