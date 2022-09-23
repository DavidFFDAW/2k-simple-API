<?php

foreach (scandir(dirname(__FILE__)) as $file) {
    if ($file === 'loader.php') {
        continue;
    }
    
    if (strpos($file, '.php') !== false) {
        require_once $file;
    }
}