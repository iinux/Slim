<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/12/23
 * Time: 22:27
 */

const MD5_FILE = 'https://raw.githubusercontent.com/iinux/Slim/zmaster/fileMd5.json';

$num = PHP_INT_MAX;
$count = 0;

if (isset($_GET['num'])) {
    $num = $_GET['num'];
}

$filesMd5Data = json_decode(file_get_contents(MD5_FILE));
foreach ($filesMd5Data as $item) {
    $localFile = __DIR__ . $item->file;
    $localFile = str_replace('\\', '/', $localFile);
    $githubFile = "https://raw.githubusercontent.com/iinux/Slim/zmaster{$item->file}";
    echo "$localFile $githubFile <br />\n";

    if (!file_exists($localFile)) {
        echo "create $localFile <br />\n";
        file_put_contents($localFile, file_get_contents($githubFile));
        $count++;
        $num--;
        if ($num <= 0) {
            break;
        }
        continue;
    }

    $currentMd5 = md5_file($localFile);
    $newestMd5 = $item->md5;
    if ($currentMd5 != $newestMd5) {
        echo "$currentMd5 != $newestMd5 update $localFile <br />\n";
        file_put_contents($localFile, file_get_contents($githubFile));
        $count++;
        $num--;
        if ($num <= 0) {
            break;
        }
    } else {
        echo "$currentMd5 == $newestMd5 skip $localFile <br />\n";
        continue;
    }
}
echo "SUCCESS update $count files <br />\n";
