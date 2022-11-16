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
     * @param string $name
     * @param integer $pagenum
     * @return Page
     */
    public function getCategory(string $name, int $pagenum = 1): Page;

    /**
     * Получить страницу тега, с пагинацией
     *
     * @param string $name
     * @param integer $pagenum
     * @return Page
     */
    public function getTag(string $name, int $pagenum = 1): Page;

    /**
     * Получить страницу поста
     *
     * @param string $name
     * @return Page
     */
    public function getPost(string $name): Page;

    /**
     * Получить сервисную страницу
     *
     * @param integer $code http код
     *
     * @return Page
     */
    public function getService(int $code): Page;
}
