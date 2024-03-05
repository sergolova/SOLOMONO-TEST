<?php

namespace Model;

use Error;

class CategoryManager
{
    private static ?CategoryManager $instance = null;
    private readonly DatabaseManager $db;
    public const TABLE_NAME = 'product_categories';
    private const CHARSET = 'utf8mb4_unicode_ci';

    public function __construct()
    {
        $this->db = DatabaseManager::getDatabaseManager();
        if (!$this->db->isTableExists(self::TABLE_NAME)) {
            $this->install();
            $this->fillDemoData();
        }
    }

    public static function getInstance(): CategoryManager
    {
        if (self::$instance === null) {
            self::$instance = new CategoryManager();
        }
        return self::$instance;
    }

    public function install(): void
    {
        $createTableQuery = 'CREATE TABLE IF NOT EXISTS ' . self::TABLE_NAME . ' (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(' . Category::constraints()['max_name_length'] . ') NOT NULL
        ) CHARACTER SET utf8mb4 COLLATE ' . self::CHARSET;

        if (!$this->db->conn->query($createTableQuery)) {
            throw new Error('Error creating table:' . $this->db->conn->error);
        }
    }

    public function fillDemoData(): void
    {
        $categories = ['Смартфони', 'Навушники', 'Камери', 'Планшети', 'Геймпади'];

        foreach ($categories as $category) {
            $insertCategoryQuery = 'INSERT INTO ' . self::TABLE_NAME . ' (name) VALUES (?)';
            $statement = $this->db->conn->prepare($insertCategoryQuery);
            $statement->bind_param('s', $category);

            if (!$statement->execute()) {
                throw new Error('Error inserting demo data:' . $statement->error);
            }
        }
    }

    public function getCategory(int $id): ?Category
    {
        $query = 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = ?';
        $statement = $this->db->conn->prepare($query);
        $row = null;

        if ($statement) {
            $statement->bind_param('i', $id);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            $row = $result->fetch_assoc();
        }
        return is_array($row) ? Category::FromArray($row) : null;
    }

    public function getCategories(): array
    {
        $query = 'SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY name ASC';
        $statement = $this->db->conn->prepare($query);

        if ($statement) {
            $statement->execute();
            $result = $statement->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $statement->close();

            return array_map(fn($el) => Category::FromArray($el), $rows);
        }
        return [];
    }
}