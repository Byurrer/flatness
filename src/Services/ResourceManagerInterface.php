<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Post;
use Flatness\Core\Resources\Containers\Tag;
use Flatness\Core\Resources\Containers\Index;
use Flatness\Core\Resources\Containers\Category;

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
     * Ключи массивов:
     *  - name - название на латинице (часть uri)
     *  - uri
     *  - frontMatter - ассоциативный массив с frontMatter данными из index.md файла
     *  - count - количество материалов
     *
     * @return array<string, mixed>
     */
    public function getCategories(): array;

    /**
     * Получить ассоциативный массив всех тегов
     *
     * Ключи массивов:
     *  - name - название на латинице (часть uri)
     *  - uri
     *  - count - количество материалов
     *
     * @return array<string, mixed>
     */
    public function getTags(): array;
}
