<?php

$envVars = getEnvironmentValues($env);

if (!defined('API_DOMAIN')) {
    define('API_DOMAIN', $envVars['API_DOMAIN']);
}

if (!defined('IMAGES_URL')) {
    define('IMAGES_URL', $envVars['IMAGES_URL']);
}