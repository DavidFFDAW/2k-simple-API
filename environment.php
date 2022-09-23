<?php

if (!defined('DEV') && !defined('PROD')) {
    define('DEV', 0);
    define('PROD', 1);
}

function getEnvironmentValues ($isProd) {
    if ($isProd) return array(
        'API_DOMAIN' => '/2k/api/v2',
        'IMAGES_URL' => 'http://vps-f87b433e.vps.ovh.net/2k/images/',
    );
    
    return array(
        'API_DOMAIN' => '/2k-simple-API',
        'IMAGES_URL' => 'http://vps-f87b433e.vps.ovh.net/2k/images/',
    );
} 