<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\ResourceContainer;

/**
 * Интерфейс шаблонизатора
 */
interface TemplaterInterface
{
    /**
     * Сделать html из данных
     *
     * @param string $template шаблон php
     * @param array $data данные для шаблона
     * @return string
     */
    public function make(string $template, array $data = []): string;

    /**
     * Сделать html из контейнера ресурсов
     *
     * @param string $template шаблон php
     * @param ResourceContainer $resources
     * @return string
     */
    public function makeFromContainer(string $template, ResourceContainer $resources);

    /**
     * Сделать html пагинации
     *
     * @param string $template шаблон php
     * @param string $uri
     * @param integer $currPage
     * @param integer $countPage
     * @return string
     */
    public function makePagination(string $template, string $uri, int $currPage, int $countPage): string;
}
