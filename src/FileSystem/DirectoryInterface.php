<?php

namespace Flatness\Core\FileSystem;

/**
 * Интерфейс директории
 */
interface DirectoryInterface
{
    /**
     * Выборка файла с инкрементом смещения (для выборки в цикле)
     *
     * @return FileInterface|null
     */
    public function getFileIncr(): ?FileInterface;

    /**
     * Установить смещение для выборки файла
     *
     * @param integer $offset
     * @return self
     */
    public function setOffset(int $offset): self;

    /**
     * Получить смещение для выборки файлов
     *
     * @return integer
     */
    public function getOffset(): int;

    /**
     * Получить общее количество файлов (без учета индексных файлов)
     *
     * @return integer
     */
    public function getTotal(): int;

    /**
     * Получить иднексный файл директории с описанием
     *
     * @return FileInterface
     */
    public function getIndex(): FileInterface;

    /**
     * Получить название деректории
     *
     * @return string
     */
    public function getName(): string;
}
