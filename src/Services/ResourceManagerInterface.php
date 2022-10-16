<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Page;

/**
 * Интерфейс менеджера ресурсов
 */
interface ResourceManagerInterface
{
    /**
     * Получить главную страницу, с пагинацией
     *
     * @param integer $pagenum
     * @return Page
     */
    public function getIndex(int $pagenum = 1): Page;

    /**
     * Получить страницу категории, с пагинацией
     *
     * @param string $uri
     * @param integer $pagenum
     * @return Page
     */
    public function getCategory(string $uri, int $pagenum = 1): Page;

    /**
     * Получить страницу тега, с пагинацией
     *
     * @param string $uri
     * @param integer $pagenum
     * @return Page
     */
    public function getTag(string $uri, int $pagenum = 1): Page;

    /**
     * Получить страницу поста
     *
     * @param string $uri
     * @return Page
     */
    public function getPost(string $uri): Page;

    /**
     * Получить сервисную страницу 404
     *
     * @return Page
     */
    public function getService404(): Page;
}
