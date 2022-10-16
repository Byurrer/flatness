<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Page;
use Flatness\Core\Resources\ResourceAbstract;
use Flatness\Core\Resources\ContainerAbstract;

/**
 * Интерфейс шаблонизатора
 */
interface TemplaterInterface
{
    /**
     * Создать страницу из ресурса
     *
     * @param ResourceAbstract $resource
     * @return Page
     */
    public function makePage(ResourceAbstract $resource): Page;

    /**
     * Сгенерировать html карточку ресурса
     *
     * @param ResourceAbstract $resource
     * @return string
     */
    public function makeCard(ResourceAbstract $resource): string;

    /**
     * Сгенерировать html пагниации
     *
     * @param ContainerAbstract $resource
     * @param integer $currPage
     * @param integer $countPage
     * @return string
     */
    public function makePagination(ContainerAbstract $resource, int $currPage, int $countPage): string;

    //######################################################################

    /**
     * Сгенерировать 404 страницу
     *
     * @return Page
     */
    public function make404(): Page;
}
