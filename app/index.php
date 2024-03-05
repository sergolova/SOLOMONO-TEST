<?php

use Model\Router;

require_once "autoloader.php";
require_once "init.php";

// RESOURCE MANAGEMENT

if (isStaticResourceRequest()) {
    return false; // true, die or exit not work
}

// APPLICATION START
Router::getInstance()->route($_SERVER['REQUEST_URI']);