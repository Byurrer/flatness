<?php

namespace Flatness;

interface PostInterface
{
    public function getHeader(): string;

    public function getDescription(): string;

    public function getUri(): string;

    public function getHtmlContent(): string;

    public function getTags(): array;

    public function getMeta(): array;

    public function getPath(): string;

    public function toArray(): array;
}
