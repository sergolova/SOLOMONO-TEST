<?php

namespace Controller;

use Model\ProductManager;
use Model\CategoryManager;
use Model\Router;

class CommonController
{
    protected ProductManager $productManager;
    protected CategoryManager $categoryManager;
    protected Router $router;

    public function __construct()
    {
        $this->productManager = ProductManager::getInstance();
        $this->categoryManager = CategoryManager::getInstance();
        $this->router = Router::getInstance();
    }

    /**
     * @param string $name -template name relative to templates folder
     * @param array $args - variables that will be available in the template
     * @return void
     */
    public function getTemplate(string $name, array $args = []): void
    {
        $file = TEMPLATES_DIR . DIRECTORY_SEPARATOR . $name . '.php';
        if (file_exists($file)) {
            extract($args); // creating global variables for the template
            include $file;
        } else {
            echo("Template not found: $file <br>\n");
        }
    }

    public function sendJson(array $json): void
    {
        $str = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo($str);
    }

    /** Shows an error in the error template
     * @param string $message
     * @param int $code - HTTP response code
     * @return never
     */
    public function exitWithError(string $message = '', int $code = 0): never
    {
        $args = [
            'styles' => ['main']
        ];

        if ($message) {
            $args['message'] = $message;
        }
        if ($code) {
            $args['code'] = $code;
        }

        http_response_code($code);
        $this->getTemplate('ErrorTemplate', $args);
        exit;
    }

    public function notFound(): never
    {
        $this->exitWithError('Not found :(', 404);
    }
}