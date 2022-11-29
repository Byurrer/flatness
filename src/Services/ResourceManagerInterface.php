<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Post;
use Flatness\Core\Resources\ResourceContainer;

/**
 * Интерфейс менеджера ресурсов
 */
interface ResourceManagerInterface
{
    /**
     * Получить главную страницу, с пагинацией
     *
     * @param integer $pagenum
     * @return ResourceContainer|null
     */
    public function getIndex(int $pagenum = 1): ?ResourceContainer;

    /**
     * Получить страницу категории, с пагинацией
     *
     * @param string $name
     * @param integer $pagenum
     * @return ResourceContainer|null
     */
    public function getCategory(string $name, int $pagenum = 1): ?ResourceContainer;

    /**
     * Получить страницу тега, с пагинацией
     *
     * @param string $name
     * @param integer $pagenum
     * @return ResourceContainer|null
     */
    public function getTag(string $name, int $pagenum = 1): ?ResourceContainer;

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
