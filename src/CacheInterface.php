<?php

namespace Flatness;

/**
 * Интерфейс кэша
 */
interface CacheInterface
{
    /**
     * Получить произвольные данные из кэша
     *
     * @param string $uri
     * @return mixed
     */
    public function get(string $uri): mixed;

    /**
     * Сохранить произвольные данные в кэш
     *
     * @param string $uri
     * @param mixed $data
     * @return void
     */
    public function save(string $uri, mixed $data): void;

    /**
     * Очистка кэша
     *
     * @return void
     */
    public function clear(): void;
}
