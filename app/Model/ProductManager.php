<?php

namespace Model;

use Error;
use Exception;

class ProductManager
{
    private static ?ProductManager $instance = null;
    private readonly DatabaseManager $db;
    public const TABLE_NAME = 'products';
    private const CHARSET = 'utf8mb4_unicode_ci';

    public function __construct()
    {
        $this->db = DatabaseManager::getDatabaseManager();
        if (!$this->db->isTableExists(self::TABLE_NAME)) {
            $this->install();
            $this->fillDemoData();
        }
    }

    public static function getInstance(): ProductManager
    {
        if (self::$instance === null) {
            self::$instance = new ProductManager();
        }
        return self::$instance;
    }

    public function install(): void
    {
        $createTableQuery = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(" . Product::constraints()['max_name_length'] . ") NOT NULL ,
        price DECIMAL(" . Product::constraints()['max_price_length'] . ", " . Product::constraints()['price_precision'] . ") NOT NULL ,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        category_id INT(6) UNSIGNED,
        FOREIGN KEY (category_id) REFERENCES " . CategoryManager::TABLE_NAME . "(id)
        ) CHARACTER SET utf8mb4 COLLATE " . self::CHARSET;
        if (!$this->db->conn->query($createTableQuery)) {
            throw new Error('Error creating table:' . $this->db->conn->error);
        }
    }

    public function fillDemoData(): void
    {
        $insertTableQuery = "INSERT IGNORE INTO ".self::TABLE_NAME." (name, price, created_at, category_id) VALUES
('Смартфон iPhone 12', 799.99, '2023-01-15', 1),
('Смартфон Samsung Galaxy S21', 699.99, '2023-02-20', 1),
('Смартфон Google Pixel 6', 899.99, '2023-03-10', 1),
('Смартфон OnePlus 9', 999.99, '2023-04-05', 1),
('Смартфон Xiaomi Mi 11', 1099.99, '2023-05-18', 1),
('Смартфон Huawei P40', 1199.99, '2023-06-22', 1),
('Смартфон Sony Xperia 5 III', 1299.99, '2023-07-30', 1),
('Навушники AirPods Pro', 249.99, '2023-01-22', 2),
('Навушники Sony WH-1000XM4', 349.99, '2023-02-25', 2),
('Навушники Bose QuietComfort 35 II', 299.99, '2023-03-15', 2),
('Навушники Jabra Elite 85t', 199.99, '2023-04-10', 2),
('Навушники Samsung Galaxy Buds Pro', 179.99, '2023-05-25', 2),
('Камера Canon EOS R5', 3499.99, '2023-01-05', 3),
('Камера Sony Alpha A7 III', 1999.99, '2023-02-08', 3),
('Камера Nikon Z6', 1799.99, '2023-03-20', 3),
('Камера Panasonic Lumix GH5', 2299.99, '2023-04-15', 3),
('Камера Fujifilm X-T4', 1699.99, '2023-05-30', 3),
('Планшет iPad Pro', 1099.99, '2023-01-10', 4),
('Планшет Samsung Galaxy Tab S7', 799.99, '2023-02-12', 4),
('Планшет Huawei MatePad Pro', 899.99, '2023-03-25', 4),
('Планшет Lenovo Tab P11', 299.99, '2023-04-18', 4),
('Планшет Amazon Fire HD 10', 149.99, '2023-05-22', 4);";

        if (!$this->db->conn->query($insertTableQuery)) {
            throw new Error('Error insert demo data:' . $this->db->conn->error);
        }
    }

    public function getProduct(int $id): ?Product
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
        return is_array($row) ? Product::FromArray($row) : null;
    }

    public function getProducts(int $category_id, string $sortKey, string $sortDir): array
    {
        $query = 'SELECT * FROM ' . self::TABLE_NAME . " WHERE category_id = $category_id ORDER BY $sortKey $sortDir";
        $statement = $this->db->conn->prepare($query);

        if ($statement) {
            $statement->execute();
            $result = $statement->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $statement->close();

            return array_map(fn($el) => Product::FromArray($el), $rows);
        }
        return [];
    }

    public function getProductsCount(int $category_id): int
    {
        $query = 'SELECT COUNT(id) as c FROM ' . self::TABLE_NAME . " WHERE category_id = $category_id";
        $statement = $this->db->conn->prepare($query);

        if ($statement) {
            $statement->execute();
            $result = $statement->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $statement->close();

            return $rows[0]['c'];
        }
        return 0;
    }
}