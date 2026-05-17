<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$db_file = __DIR__ . '/database.sqlite';
$pdo = new PDO('sqlite:' . $db_file);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT NOT NULL,
    price REAL NOT NULL,
    image TEXT NOT NULL,
    description TEXT
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name TEXT NOT NULL,
    phone TEXT NOT NULL,
    address TEXT NOT NULL,
    total_amount REAL NOT NULL,
    status TEXT DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price REAL NOT NULL,
    FOREIGN KEY(order_id) REFERENCES orders(id)
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
        // Check if it's an order or a product
        if (isset($input['customer_name'])) {
            // It's an order
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("INSERT INTO orders (customer_name, phone, address, total_amount) VALUES (:name, :phone, :address, :total)");
                $stmt->execute([
                    ':name' => $input['customer_name'],
                    ':phone' => $input['phone'],
                    ':address' => $input['address'],
                    ':total' => $input['total_amount']
                ]);
                $order_id = $pdo->lastInsertId();

                $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
                foreach ($input['items'] as $item) {
                    $stmt_item->execute([
                        ':order_id' => $order_id,
                        ':product_id' => $item['id'],
                        ':quantity' => $item['quantity'],
                        ':price' => $item['price']
                    ]);
                }
                $pdo->commit();
                echo json_encode(['id' => $order_id, 'message' => 'Order placed successfully']);
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }
        } else {
            // It's a product (admin use case)
            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, image, description) VALUES (:name, :category, :price, :image, :description)");
            $stmt->execute([
                ':name' => $input['name'],
                ':category' => $input['category'],
                ':price' => $input['price'],
                ':image' => $input['image'] ?? '',
                ':description' => $input['description'] ?? ''
            ]);
            echo json_encode(['id' => $pdo->lastInsertId(), 'message' => 'Product created']);
        }
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
