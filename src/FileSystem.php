<?php

namespace Flatness;

class FileSystem implements FileSystemInterface
{
    public function loadFile(string $path): string
    {
        return file_get_contents($path);
    }

    public function saveFile(string $path, string $data): void
    {
        file_put_contents($path, $data);
    }

    public function existsFile(string $path): bool
    {
        return file_exists($path);
    }

    public function getDirs(string $path): array
    {
        $dirs = $this->scandir($path);

        foreach ($dirs as &$dir) {
            $dir = str_ireplace($path, '', $dir);
        }

        usort(
            $dirs,
            function (string $a, string $b) {
                $a = dirname($a);
                $b = dirname($b);
                if ($a == $b) {
                    return 0;
                }
                return ($a > $b) ? -1 : 1;
            }
        );

        return $dirs;
    }

    public function searchDir(string $dir, string $needle): ?string
    {
        if ($res = $this->searchDirRec($dir, $needle)) {
            $res = str_ireplace($dir, '', $res);
            return $res;
        }

        return null;
    }

    public function mkDir(string $path): bool
    {
        if (!file_exists($path)) {
            return mkdir($path, 0777, true);
        }

        return false;
    }

    public function filemtime(string $filename): int
    {
        return intval(filemtime($filename));
    }

    public function rmDir(string $path): void
    {
        if (is_file($path)) {
            unlink($path);
            return;
        } elseif (is_dir($path)) {
            $a = scandir($path);
            foreach ($a as $p) {
                if (($p != '.') && ($p != '..')) {
                    $this->rmDir($path . DIRECTORY_SEPARATOR . $p);
                }
            }
            rmdir($path);
        }
    }

    //######################################################################
    // PRIVATE
    //######################################################################

    private function scandir(string $dirPath): array
    {
        $a = scandir($dirPath);
        $dirs = [];

        foreach ($a as $path) {
            if ($path == '.' || $path == '..') {
                continue;
            }

            $path = $dirPath . '/' . $path;
            if (is_dir($path)) {
                if (file_exists($path . '/index.md')) {
                    $dirs[] = $path;
                } elseif ($res = $this->scandir($path)) {
                    $dirs = array_merge($dirs, $res);
                }
            }
        }

        return $dirs;
    }

    private function searchDirRec(string $dir, string $needle): ?string
    {
        $a = scandir($dir);

        foreach ($a as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                if ($file == $needle) {
                    return $path;
                } elseif ($res = $this->searchDirRec($path, $needle)) {
                    return $res;
                }
            }
        }

        return null;
    }
}
