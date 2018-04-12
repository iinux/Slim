<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/12/23
 * Time: 22:22
 */

const MD5_FILE = 'fileMd5.json';

$dirs = [
    __DIR__ . '/App',
    __DIR__ . '/resources',
    __DIR__ . '/routes',
    __DIR__ . '/support',
];
$files = [
    __DIR__ . '/bootstrap/app.php',
];

$filesMd5Data = [];

foreach ($files as $file) {
    $filesMd5Data[] = [
        'file' => substr($file, strlen(__DIR__)),
        'md5'  => md5_file($file),
    ];
}

foreach ($dirs as $dir) {
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        /**
         * @var SplFileInfo $file
         */
        $realPath = $file->getRealPath();
        if ($file->isDir()) {
        } else {
            $filesMd5Data[] = [
                'file' => str_replace('\\', '/', substr($file->getPathname(), strlen(__DIR__))),
                'md5'  => md5_file($realPath),
            ];
        }
    }
}
file_put_contents(MD5_FILE, json_encode($filesMd5Data));
echo "OK\n";
