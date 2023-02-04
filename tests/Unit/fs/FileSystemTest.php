<?php

use Flatness\FileSystem;
use PHPUnit\Framework\TestCase;

define('CONTENT_DIR', __DIR__ . '/content');

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class FileSystemTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $fs = new FileSystem();
        $fs->rmdir(CONTENT_DIR);

        // 2022/01/10
        mkdir(
            sprintf('%s/2022/01/10/post0/images/subdir', CONTENT_DIR),
            0777,
            true
        );
        file_put_contents(sprintf('%s/2022/01/10/post0/index.md', CONTENT_DIR), '');

        // 2022/01/11
        mkdir(
            sprintf('%s/2022/01/11/post1/images/subdir', CONTENT_DIR),
            0777,
            true
        );
        file_put_contents(sprintf('%s/2022/01/11/post1/index.md', CONTENT_DIR), '');

        // 2022/02
        mkdir(
            sprintf('%s/2022/02/post2/images/subdir', CONTENT_DIR),
            0777,
            true
        );
        file_put_contents(sprintf('%s/2022/02/post2/index.md', CONTENT_DIR), '');

        // 2022/03
        mkdir(
            sprintf('%s/2022/03/post3/images/subdir', CONTENT_DIR),
            0777,
            true
        );
        file_put_contents(sprintf('%s/2022/03/post3/index.md', CONTENT_DIR), '');

        // 2023/04
        mkdir(
            sprintf('%s/2023/04/post4/images/subdir', CONTENT_DIR),
            0777,
            true
        );
        file_put_contents(sprintf('%s/2023/04/post4/index.md', CONTENT_DIR), '');
    }

    public static function tearDownAfterClass(): void
    {
        $fs = new FileSystem();
        $fs->rmdir(CONTENT_DIR);
    }

    //######################################################################

    public function testGetDirs()
    {
        $fs = new FileSystem();
        $dirs = $fs->getDirs(CONTENT_DIR);

        //print_r($dirs);

        $this->assertIsArray($dirs);
        $this->assertCount(5, $dirs);

        $this->assertStringContainsString('2023/04/post4', $dirs[0]);
        $this->assertStringContainsString('2022/03/post3', $dirs[1]);
        $this->assertStringContainsString('2022/02/post2', $dirs[2]);
        $this->assertStringContainsString('2022/01/11/post1', $dirs[3]);
        $this->assertStringContainsString('2022/01/10/post0', $dirs[4]);
    }

    public function testSearchDir()
    {
        $fs = new FileSystem();
        $this->assertSame('/2022/02/post2', $fs->searchDir(CONTENT_DIR, 'post2'));
    }
}
