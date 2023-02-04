<?php

use Flatness\Cache;
use PHPUnit\Framework\TestCase;
use Flatness\FileSystemInterface;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class CacheTest extends TestCase
{
    public function test()
    {
        $data = ['a' => 'b'];
        $fs = $this->createMock(FileSystemInterface::class);
        $fs->expects($this->exactly(1))->method('loadFile')->willReturn(json_encode($data));
        $fs->expects($this->exactly(1))->method('saveFile');
        $fs->expects($this->exactly(2))->method('existsFile')->willReturn(true);
        $fs->expects($this->exactly(1))->method('filemtime')->willReturn(time());

        $cache = new Cache($fs, '/');

        $cache->save('uri', $data);
        $cache->get('uri');
    }
}
