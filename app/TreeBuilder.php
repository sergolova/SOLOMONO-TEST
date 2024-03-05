<?php

function buildCategoryTree(\Model\DatabaseManager $db)
{
    // 7 levels must хватить :)
    $query = 'SELECT
        L0.categories_id AS Cat0,
        L1.categories_id AS Cat1,
        L2.categories_id AS Cat2,
        L3.categories_id AS Cat3,
        L4.categories_id AS Cat4,
        L5.categories_id AS Cat5,
        L6.categories_id AS Cat6,
        L7.categories_id AS Cat7
    FROM
        categories AS L0
            LEFT JOIN categories AS L1 ON L0.categories_id = L1.parent_id
            LEFT JOIN categories AS L2 ON L1.categories_id = L2.parent_id
            LEFT JOIN categories AS L3 ON L2.categories_id = L3.parent_id
            LEFT JOIN categories AS L4 ON L3.categories_id = L4.parent_id
            LEFT JOIN categories AS L5 ON L4.categories_id = L5.parent_id
            LEFT JOIN categories AS L6 ON L5.categories_id = L6.parent_id
            LEFT JOIN categories AS L7 ON L6.categories_id = L7.parent_id
    WHERE
        L0.parent_id = 0';

    $result = $db->conn->query($query);

    $tree = [];

    while ($row = $result->fetch_assoc()) {
        $currentNode = &$tree;

        foreach ($row as $level => $categoryId) {
            if ($categoryId === null) {
                break;
            }

            if (!isset($currentNode[$categoryId])) {
                $currentNode[$categoryId] = [];
            }

            $currentNode = &$currentNode[$categoryId];
        }
    }

    return $tree;
}

function transformEmptyArrays(int $key, array &$node): void
{
    if (empty($node)) {
        $node = $key;
    } else {
        foreach ($node as $k => &$childNode) {
            transformEmptyArrays($k, $childNode);
        }
    }
}

function addTestTable(\Model\DatabaseManager $db)
{
    if (!$db->isTableExists('categories')) {
        $sql = file_get_contents('..' . DIRECTORY_SEPARATOR . 'access' . DIRECTORY_SEPARATOR . 'test.sql');

        $sqlQueries = explode(';', $sql);

        foreach ($sqlQueries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                if ($db->conn->query($query) === FALSE) {
                    echo 'An error occurred while executing the request: ' . $db->conn->error;
                }
            }
        }
    }
}

$db = \Model\DatabaseManager::getDatabaseManager();
addTestTable($db);
$categoryTree = buildCategoryTree($db);
transformEmptyArrays(0, $categoryTree);
print_r($categoryTree);