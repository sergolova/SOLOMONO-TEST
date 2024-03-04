<?php

namespace Controller;

use Model\Category;
use Model\Product;
use Model\Router;
use \Exception as Exception;

class ProductController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home(): void
    {
        $sort_name = 'alphabetically';
        $categories = $this->categoryManager->getCategories();
        $current_category = $categories ? $categories[0]->id : 0;

        $categoriesEx = array_map(function (
            Category $cat) {
            return [
                'cat' => $cat,
                'numProducts' => $this->productManager->getProductsCount($cat->id)
            ];
        }, $categories);

        try {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $current_category = (int)trim($_GET['category'] ?? $current_category);
                $sort_name = trim($_GET['sort_name'] ?? '');
            }
        } catch (Exception) {
            http_response_code(500);
        } finally {
            $this->getTemplate('ProductsTemplate', [
                'categories' => $categoriesEx,
                'currentCategory' => $current_category,
                'sortName' => $sort_name,
                'styles' => ['main'],
            ]);
        }
    }

    public function getProductsAjax(): void
    {
        $products = [];

        try {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $current_category = (int)trim($_GET['category_id'] ?? '');
                $sort_name = trim($_GET['sort_name'] ?? '');

                if ($sort_name === 'cheap_first') {
                    $sortKey = 'price';
                    $sortDir = 'ASC';
                } elseif ($sort_name === 'new_first') {
                    $sortKey = 'created_at';
                    $sortDir = 'ASC';
                } else { // if ($sort_name === 'alphabetically')
                    $sortKey = 'name';
                    $sortDir = 'ASC';
                }

                $products = $this->productManager->getProducts($current_category, $sortKey, $sortDir);
            }
        } catch (Exception) {
            http_response_code(500);
        } finally {
            $this->sendJson([
                'products' => $products,
            ]);
        }
    }
}