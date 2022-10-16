<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Page;

/**
 * Интерфейс кэша страниц
 */
interface CacheInterface
{
    /**
     * Получить главную страницу из кэша
     *
     * @param integer $pagenum
     * @return Page
     */
    public function getIndex(int $pagenum = 1): ?Page;

    /**
     * Получить страницу категории из кэша
     *
     * @param string $uri
     * @param integer $pagenum
     * @return Page|null
     */
    public function getCategory(string $uri, int $pagenum = 1): ?Page;

    /**
     * Получить страницу тега из кэша
     *
     * @param string $uri
     * @param integer $pagenum
     * @return Page|null
     */
    public function getTag(string $uri, int $pagenum = 1): ?Page;

    /**
     * Получить страницу поста из кэша
     *
     * @param string $uri
     * @return Page|null
     */
    public function getPost(string $uri): ?Page;

    /**
     * Сохранить кэш страницы
     *
     * @param Page $page
     * @return void
     */
    public function save(Page $page): void;

    /**
     * Очистка кэша
     *
     * @return void
     */
    public function clear();
}
