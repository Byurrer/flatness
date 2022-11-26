<?php

namespace Flatness\Core\Services;

/**
 * Интерфейс кэша страниц
 */
interface CacheInterface
{
    /**
     * Получить страницу из кэша
     *
     * @param string $uri
     * @return string|null
     */
    public function getPage(string $uri): ?string;

    /**
     * Сохранить страницу в кэш
     *
     * @param string $page
     * @param string $uri
     * @return void
     */
    public function savePage(string $uri, string $page): void;

    //######################################################################

    /**
     * Получить произвольные данные из кэша
     *
     * @param string $uri
     * @return array|null
     */
    public function getData(string $uri): ?array;

    /**
     * Сохранить произвольные данные в кэш
     *
     * @param string $uri
     * @param array $data
     * @return void
     */
    public function saveData(string $uri, array $data): void;

    //######################################################################

    /**
     * Очистка кэша
     *
     * @return void
     */
    public function clear();
}
