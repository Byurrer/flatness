<?php

namespace Flatness\Core\FileSystem;

/**
 * Интерфейс файла
 */
interface FileInterface
{
    /**
     * Получить родительские директории (без учета относительного пути)
     *
     * @return array
     */
    public function getParents(): array;

    /**
     * Получить контент файла
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Получить название файла (оно же uri)
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Получить расширение файла
     *
     * @return string
     */
    public function getExt(): string;

    /**
     * Получить относительный путь до файла (относительно корню директории с файлами)
     *
     * @return string
     */
    public function getRelPath(): string;
}
