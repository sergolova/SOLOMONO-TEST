<?php

use Model\Router;

require_once "init.php";
require_once "autoloader.php";

// RESOURCE MANAGEMENT

if (isStaticResourceRequest()) {
    return false; // true, die or exit not work
}

// APPLICATION START

//$d = \Model\DatabaseManager::getDatabaseManager();
//$с = \Model\CategoryManager::getInstance();
//$p = \Model\ProductManager::getInstance();


Router::getInstance()->route($_SERVER['REQUEST_URI']);