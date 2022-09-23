<?php

$parentDir = dirname(__FILE__) . SP . 'parent';

if (is_dir($parentDir)) {
    load($parentDir);
}

load(dirname(__FILE__));