<?php

namespace Flatness;

interface PostListInterface
{
    public function next(): ?PostInterface;

    public function offset(int $offset = null): int;

    public function total(): int;

    public function count(): int;
}
