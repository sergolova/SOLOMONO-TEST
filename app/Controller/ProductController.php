<?php

namespace Controller;

use Model\Category;

class ProductController extends CommonController
{
    const SORT_OPTIONS = [
        'alphabetically' => 'A -> Z',
        'cheap_first' => 'Cheap first',
        'new_first' => 'New first'
    ];

    public function home(): void
    {
        $sort_name = array_keys(self::SORT_OPTIONS)[0]; // default
        $categories = $this->categoryManager->getCategories();
        $current_category = $categories ? $categories[0]->id : 0; // default

        $categoriesEx = array_map(fn(Category $cat) => [
            'cat' => $cat,
            'numProducts' => $this->productManager->getProductsCount($cat->id)
        ], $categories);

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $current_category = (int)trim(@$_GET['category_id'] ?? $current_category);
            $sort_name = trim(@$_GET['sort_name'] ?? $sort_name);
        }

        $this->getTemplate('ProductsTemplate', [
            'categories' => $categoriesEx,
            'currentCategory' => $current_category,
            'sortName' => $sort_name,
            'sortOptions' => self::SORT_OPTIONS,
            'styles' => ['main'],
        ]);
    }

    public function getProductsAjax(): void
    {
        $products = [];
        $sort_name = array_keys(self::SORT_OPTIONS)[0]; // default

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $category = (int)trim(@$_GET['category_id']);
            $sort_name = trim(@$_GET['sort_name'] ?? $sort_name);
            $sortDir = 'ASC';

            if ($sort_name === 'cheap_first') {
                $sortKey = 'price';
            } elseif ($sort_name === 'new_first') {
                $sortKey = 'created_at';
            } else { // if ($sort_name === 'alphabetically')
                $sortKey = 'name';
            }
            $products = $this->productManager->getProducts($category, $sortKey, $sortDir);
        }

        $this->sendJson([
            'products' => $products,
        ]);
    }
}