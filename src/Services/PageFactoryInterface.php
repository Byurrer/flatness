<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Page;

/**
 * Интерфейс фабрики страниц
 */
interface PageFactoryInterface
{
    /**
     * Сделать главную страницу
     *
     * @param integer $pagenum
     * @return Page
     */
    public function makeIndex(int $pagenum = 1): Page;

    /**
     * Сделать страницу категории
     *
     * @param string $uri
     * @param integer $pagenum
     * @return Page
     */
    public function makeCategory(string $uri, int $pagenum = 0): Page;

    /**
     * Сделать страницу тега
     *
     * @param string $uri
     * @param integer $pagenum
     * @return Page
     */
    public function makeTag(string $uri, int $pagenum = 0): Page;

    /**
     * Сделать страницу поста
     *
     * @param string $uri
     * @return Page
     */
    public function makePost(string $uri): Page;

    /**
     * Сгенерировать сервисную страницу
     *
     * @param integer $code http код
     *
     * @return Page
     */
    public function makeService(int $code): Page;
}
