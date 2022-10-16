<?php

namespace Flatness\Core\Resources;

/**
 * Рисуемый объект
 */
interface RenderableInterface
{
    /**
     * Получить контент объекта в HTML
     *
     * @return string
     */
    public function getHtmlContent(): string;
}
