<?php
$files = glob(__DIR__ . '/storage/framework/views/*');
foreach($files as$file){
    if(is_file($file)) {
        @unlink($file);
    }
}
echo "Cache views cleared!";
