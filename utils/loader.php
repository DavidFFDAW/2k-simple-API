<?php

foreach (scandir(dirname(__FILE__)) as $file) {
    if (strpos($file, '.php') !== false) {
        require_once $file;
    }
}