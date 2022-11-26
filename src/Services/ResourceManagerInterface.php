<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Tag;
use Flatness\Core\Resources\Post;
use Flatness\Core\Resources\Index;
use Flatness\Core\Resources\Category;

/**
 * Интерфейс менеджера ресурсов
 */
interface ResourceManagerInterface
{
    /**
     * Получить главную страницу, с пагинацией
     *
     * @param integer $pagenum
     * @return Index|null
     */
    public function getIndex(int $pagenum = 1): ?Index;

    /**
     * Получить страницу категории, с пагинацией
     *
     * @param string $name
     * @param integer $pagenum
     * @return Category|null
     */
    public function getCategory(string $name, int $pagenum = 1): ?Category;

    /**
     * Получить страницу тега, с пагинацией
     *
     * @param string $name
     * @param integer $pagenum
     * @return Tag|null
     */
    public function getTag(string $name, int $pagenum = 1): ?Tag;

    /**
     * Получить страницу поста
     *
     * @param string $name
     * @return Post|null
     */
    public function getPost(string $name): ?Post;

    /**
     * Получить ассоциативный массив всех категорий
     *
     * @return array
     */
    public function getCategories(): array;

    /**
     * Получить ассоциативный массив всех тегов
     *
     * @return array
     */
    public function getTags(): array;
}
