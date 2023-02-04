<?php

namespace Flatness;

interface FileSystemInterface
{
    public function loadFile(string $path): string;

    public function saveFile(string $path, string $data): void;

    public function filemtime(string $filename): int;

    public function existsFile(string $path): bool;

    public function mkDir(string $path): bool;

    public function rmDir(string $dir): void;

    /**
     * Получить линейный список всех конечных директорий.
     * Аналогично - найти все статьи
     *
     * @param string $path
     * @return array
     */
    public function getDirs(string $path): array;

    public function searchDir(string $root, string $needle): ?string;
}
