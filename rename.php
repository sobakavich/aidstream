<?php

function renameFiles()
{
    $fileNames = explode("\n", shell_exec('ls'));

    foreach ($fileNames as $fileName) {
        $name     = explode('.', $fileName)[0];
        $metaData = explode('-', $name);
        $code     = end($metaData);

        foreach ($metaData as $index => $value) {
            if ($value == $code) {
                unset($metaData[$index]);
            }
        }

        if (ctype_upper($code)) {
            $code = strtolower($code);
        }

        $publisherId = implode('-', $metaData);
        $newFileName = sprintf('%s-%s.xml', $publisherId, $code);

        if ($fileName != $newFileName) {
            shell_exec(sprintf("mv %s %s", $fileName, $newFileName));
        }
    }
}

renameFiles();

?>
