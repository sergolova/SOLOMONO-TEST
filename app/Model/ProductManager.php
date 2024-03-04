<?php

namespace Model;

use Error;
use Exception;

class ProductManager
{
    private static ?ProductManager $instance = null;
    private readonly DatabaseManager $db;
    private const TABLE_NAME = 'products';
    private const CHARSET = 'utf8mb4_unicode_ci';

    public function __construct()
    {
        $this->db = DatabaseManager::getDatabaseManager();
        if (!$this->isTableExists(self::TABLE_NAME)) {
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
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ) CHARACTER SET utf8mb4 COLLATE " . self::CHARSET;

        if (!$this->db->conn->query($createTableQuery)) {
            throw new Error('Error creating table:' . $this->db->conn->error);
        }
    }

    public function isTableExists(string $tableName): bool
    {
        $checkTableQuery = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$tableName'";
        $result = $this->db->conn->query($checkTableQuery);

        return $result && $result->num_rows > 0;
    }

    public function fillDemoData(): void
    {
        $insertTableQuery = "INSERT IGNORE INTO products (name, price, created_at, category_id) VALUES
('Ноутбук ASUS VivoBook', 799.99, '2023-01-01 12:00:00', 1),
('Смартфон Samsung Galaxy S21', 899.99, '2023-02-15 15:30:00', 2),
('Гарнітура Sony WH-1000XM4', 349.99, '2023-03-10 08:45:00', 3),
('Планшет Apple iPad Pro', 1099.99, '2023-04-20 18:20:00', 4),
('Ноутбук Dell XPS 13', 1299.99, '2023-05-05 10:10:00', 1),
('Смарт-годинник Apple Watch Series 6', 399.99, '2023-06-30 21:45:00', 2),
('Фотокамера Canon EOS R5', 3499.99, '2023-07-12 14:15:00', 3),
('Телевізор LG OLED CX', 1499.99, '2023-08-25 09:30:00', 4),
('Ігрова консоль Sony PlayStation 5', 499.99, '2023-09-05 17:00:00', 1),
('Навушники Bose QuietComfort 35 II', 299.99, '2023-10-18 12:40:00', 2),
('Смартфон Google Pixel 6', 699.99, '2023-11-01 08:00:00', 3),
('Ноутбук HP Spectre x360', 1199.99, '2023-12-15 19:20:00', 4),
('Смарт-колонка Amazon Echo Dot', 49.99, '2024-01-10 14:50:00', 1),
('Фітнес-трекер Fitbit Charge 4', 149.99, '2024-02-22 11:15:00', 2),
('Монітор Samsung Odyssey G9', 1299.99, '2024-03-08 16:30:00', 3),
('Відеокарта NVIDIA GeForce RTX 3080', 699.99, '2024-04-18 22:10:00', 4),
('Електронна книга Kindle Paperwhite', 129.99, '2024-05-30 10:05:00', 1),
('Геймпад Xbox Wireless Controller', 59.99, '2024-06-14 13:25:00', 2),
('Робот-пилосос iRobot Roomba', 299.99, '2024-07-20 15:50:00', 3),
('Смарт-термостат Nest Learning Thermostat', 249.99, '2024-08-05 09:15:00', 4);";

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
        $query = 'SELECT * FROM ' . self::TABLE_NAME . " WHERE category_id = $category_id";
        $statement = $this->db->conn->prepare($query);

        if ($statement) {
            $statement->execute();
            $result = $statement->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $statement->close();

            return count($rows);
        }
        return 0;
    }
}