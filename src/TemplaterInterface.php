<?php

namespace Flatness;

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
     * Сделать html из списка постов
     *
     * @param string $template шаблон php
     * @param PostList $postList
     * @param int $limit
     * @return string
     */
    public function makeFromList(string $template, PostList $postList, int $limit = 0);

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
