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
     * @param string $path
     * @param integer $pagenum
     * @return Page
     */
    public function makeCategory(string $path, int $pagenum = 0): Page;

    /**
     * Сделать страницу тега
     *
     * @param string $name
     * @param integer $pagenum
     * @return Page
     */
    public function makeTag(string $name, int $pagenum = 0): Page;

    /**
     * Сделать страницу поста
     *
     * @param string $name
     * @return Page
     */
    public function makePost(string $name): Page;

    /**
     * Сгенерировать сервисную страницу
     *
     * @param integer $code http код
     *
     * @return Page
     */
    public function makeService(int $code): Page;
}
