<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\ResourceAbstract;
use Flatness\Core\Resources\ContainerAbstract;

/**
 * Интерфейс шаблонизатора
 */
interface TemplaterInterface
{
    /**
     * Сделать страницу из ресурса
     *
     * @param ResourceAbstract $resource
     * @return string
     */
    public function makePageFromResource(ResourceAbstract $resource): string;

    /**
     * Сделать html карточку ресурса
     *
     * @param ResourceAbstract $resource
     * @return string
     */
    public function makeCard(ResourceAbstract $resource): string;

    /**
     * Сделать html пагинации
     *
     * @param ContainerAbstract $resource
     * @param integer $currPage
     * @param integer $countPage
     * @return string
     */
    public function makePagination(ContainerAbstract $resource, int $currPage, int $countPage): string;

    //######################################################################

    /**
     * Сделать сервисную страницу
     *
     * @param integer $code http код
     *
     * @return string
     */
    public function makeService(int $code): string;

    //######################################################################

    /**
     * Сделать страницу из данных
     *
     * @param string $template шаблон php
     * @param string $uri
     * @param string $type
     * @param array $data данные для шаблона
     * @return string
     */
    public function makePage(string $template, string $uri, string $type, array $data = []): string;
}
