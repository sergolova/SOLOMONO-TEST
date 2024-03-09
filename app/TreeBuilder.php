<?php

function buildCategoryTree(\Model\DatabaseManager $db): array
{
    $query = "WITH RECURSIVE CategoryHierarchy AS (
    SELECT
        categories_id,
        CAST(categories_id AS CHAR(256)) AS path
    FROM categories WHERE parent_id = 0

    UNION ALL

    SELECT
        c.categories_id,
        CONCAT(ch.path, ' ', c.categories_id)
    FROM categories c
    JOIN CategoryHierarchy ch ON c.parent_id = ch.categories_id
)

SELECT path FROM CategoryHierarchy";

    $result = $db->conn->query($query);
    $tree = [];

    while ($row = $result->fetch_assoc()) {
        $pathElements = explode(' ', $row['path']);
        $currentLevel = &$tree;

        foreach ($pathElements as $pathElement) {
            if (!isset($currentLevel[$pathElement])) {
                $currentLevel[$pathElement] = $pathElement;
            } elseif (is_string($currentLevel[$pathElement])) {
                $currentLevel[$pathElement] = [];
            }

            $currentLevel = &$currentLevel[$pathElement];
        }
    }

    return $tree;
}

function addTestTable(\Model\DatabaseManager $db): void
{
    if (!$db->isTableExists('categories')) {
        $sql = file_get_contents('..' . DIRECTORY_SEPARATOR . 'access' . DIRECTORY_SEPARATOR . 'test.sql');

        $sqlQueries = explode(';', $sql);

        foreach ($sqlQueries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                if ($db->conn->query($query) === FALSE) {
                    echo 'An error occurred while executing the request: ' . $db->conn->error . PHP_EOL;
                }
            }
        }
    }
}

$db = \Model\DatabaseManager::getDatabaseManager();
addTestTable($db);

$start_time = microtime(true);
$categoryTree = buildCategoryTree($db);
$execution_time = microtime(true) - $start_time;
echo "TIME: $execution_time seconds\n\n";

print_r($categoryTree);