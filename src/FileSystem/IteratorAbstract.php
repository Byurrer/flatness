<?php

namespace Flatness\Core\FileSystem;

/**
 * Итератор по объектам файловой системы
 */
abstract class IteratorAbstract
{
    /**
     * @param array<string> $objects массив абсолютных путей
     * @param string $rootPath корневой путь
     */
    final public function __construct(array $objects, string $rootPath)
    {
        $this->objects = $objects;
        $this->rootPath = $rootPath;
    }

    /**
     * Получить текущий объект файловой системы (инкремент счетчика)
     *
     * @return mixed
     */
    abstract public function current();

    /**
     * Установить смещение для выборки файла
     *
     * @param integer $offset
     * @return self
     */
    public function setOffset(int $offset): self
    {
        if ($offset >= 0 && $offset < count($this->objects)) {
            $this->current = $offset;
        }
        return $this;
    }

    /**
     * Получить смещение для выборки файлов
     *
     * @return integer
     */
    public function getOffset(): int
    {
        return $this->current;
    }

    /**
     * Получить общее количество
     *
     * @return integer
     */
    public function getCount(): int
    {
        return count($this->objects);
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    /** @var array<string> */
    protected array $objects = [];

    protected int $current = 0;

    protected string $rootPath = '';
}
