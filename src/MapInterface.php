<?php

namespace Flatness;

interface MapInterface
{
    /**
     * Получить пост по uri
     *
     * @param string $uri
     * @return PostInterface|null
     */
    public function getPost(string $uri): ?PostInterface;

    /**
     * Получить список тегов
     *
     * @return array<string, array<string>>
     */
    public function getTags(): array;

    /**
     * Получить список всех постов
     *
     * @param string|null $tag
     * @return PostListInterface
     */
    public function getPosts(string $tag = null): PostListInterface;
}
