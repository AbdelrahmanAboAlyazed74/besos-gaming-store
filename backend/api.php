<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$db_file = __DIR__ . '/database.sqlite';
$pdo = new PDO('sqlite:' . $db_file);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT NOT NULL,
    price REAL NOT NULL,
    image TEXT NOT NULL,
    description TEXT
)");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Ensure the response is JSON
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

try {
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
    } elseif ($method === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, image, description) VALUES (:name, :category, :price, :image, :description)");
        $stmt->execute([
            ':name' => $input['name'],
            ':category' => $input['category'],
            ':price' => $input['price'],
            ':image' => $input['image'] ?? '',
            ':description' => $input['description'] ?? ''
        ]);
        echo json_encode(['id' => $pdo->lastInsertId(), 'message' => 'Product created']);
    } elseif ($method === 'PUT') {
        $stmt = $pdo->prepare("UPDATE products SET name = :name, category = :category, price = :price, image = :image, description = :description WHERE id = :id");
        $stmt->execute([
            ':id' => $input['id'],
            ':name' => $input['name'],
            ':category' => $input['category'],
            ':price' => $input['price'],
            ':image' => $input['image'] ?? '',
            ':description' => $input['description'] ?? ''
        ]);
        echo json_encode(['message' => 'Product updated']);
    } elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? ($input['id'] ?? null);
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['message' => 'Product deleted']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'No ID provided']);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
