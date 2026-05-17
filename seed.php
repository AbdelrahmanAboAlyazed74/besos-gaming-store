<?php
$db_file = __DIR__ . '/database.sqlite';
$pdo = new PDO('sqlite:' . $db_file);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("DROP TABLE IF EXISTS products");
$pdo->exec("DROP TABLE IF EXISTS orders");
$pdo->exec("DROP TABLE IF EXISTS order_items");

$pdo->exec("CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT NOT NULL,
    price REAL NOT NULL,
    image TEXT NOT NULL,
    description TEXT
)");

$pdo->exec("CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name TEXT NOT NULL,
    phone TEXT NOT NULL,
    address TEXT NOT NULL,
    total_amount REAL NOT NULL,
    status TEXT DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price REAL NOT NULL,
    FOREIGN KEY(order_id) REFERENCES orders(id)
)");

// Mapped realistic Unsplash images for each category
$ps_headset = 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?auto=format&fit=crop&q=80&w=600';
$ps_console = 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?auto=format&fit=crop&q=80&w=600';
$ps_controller_close = 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&q=80&w=600';
$ps_controller = 'https://images.unsplash.com/photo-1606318801954-d46d46d3360a?auto=format&fit=crop&q=80&w=600';
$general_setup = 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=600';

$controller_sticks = 'https://images.unsplash.com/photo-1593118247619-e2d6f056869e?auto=format&fit=crop&q=80&w=600';
$xbox_controller = 'https://images.unsplash.com/photo-1605901309584-818e25960b8f?auto=format&fit=crop&q=80&w=600';

$pc_mouse = 'https://images.unsplash.com/photo-1527814050087-1528c36f5647?auto=format&fit=crop&q=80&w=600';
$pc_keyboard = 'https://images.unsplash.com/photo-1595225476474-87563907a212?auto=format&fit=crop&q=80&w=600';
$pc_monitor = 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&q=80&w=600';
$pc_mic = 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&q=80&w=600';

$products = [
    // PlayStation
    ['name' => 'PS5 Pulse 3D Wireless Headset', 'category' => 'PlayStation', 'price' => 99.99, 'image' => $ps_headset, 'description' => 'Enjoy a seamless, wireless experience with a headset fine-tuned for 3D Audio on PS5 consoles.'],
    ['name' => 'PS5 HD Camera', 'category' => 'PlayStation', 'price' => 59.99, 'image' => $ps_console, 'description' => 'Featuring dual lenses for 1080p capture and a built-in stand.'],
    ['name' => 'PS5 Media Remote', 'category' => 'PlayStation', 'price' => 29.99, 'image' => $ps_controller, 'description' => 'Conveniently navigate entertainment on your PlayStation 5 console with intuitive media and TV controls.'],
    ['name' => 'PS VR2 Headset', 'category' => 'PlayStation', 'price' => 549.99, 'image' => $general_setup, 'description' => 'Escape into worlds that feel, look and sound truly real as virtual reality gaming takes a huge generational leap forward.'],
    ['name' => 'PS5 Console Covers (Midnight Black)', 'category' => 'PlayStation', 'price' => 54.99, 'image' => $ps_console, 'description' => 'Personalize your PS5 console with a vibrant array of colors.'],
    ['name' => 'PS5 DualSense Charging Station', 'category' => 'PlayStation', 'price' => 29.99, 'image' => $ps_controller_close, 'description' => 'Charge up to two DualSense wireless controllers simultaneously without having to connect them to your PlayStation 5 console.'],
    ['name' => 'PlayStation Link USB Adapter', 'category' => 'PlayStation', 'price' => 24.99, 'image' => $general_setup, 'description' => 'Enjoy a lossless, lightning-fast wireless audio connection.'],
    ['name' => 'PS4 Gold Wireless Headset', 'category' => 'PlayStation', 'price' => 79.99, 'image' => $ps_headset, 'description' => 'Discover how great your games can sound with the headset that offers extraordinary audio quality.'],

    // Controller Accessories
    ['name' => 'KontrolFreek FPS Freek Galaxy', 'category' => 'Controller Accessories', 'price' => 16.99, 'image' => $controller_sticks, 'description' => 'Performance Thumbsticks for PS5 and PS4 controllers.'],
    ['name' => 'eXtremeRate PlayVital Controller Grip', 'category' => 'Controller Accessories', 'price' => 14.99, 'image' => $ps_controller_close, 'description' => 'Anti-slip silicone cover for PS5 controller.'],
    ['name' => 'SCUF Instinct Pro Performance Controller', 'category' => 'Controller Accessories', 'price' => 229.99, 'image' => $xbox_controller, 'description' => 'Custom performance controller designed for Xbox and PC.'],
    ['name' => 'Xbox Elite Wireless Controller Series 2', 'category' => 'Controller Accessories', 'price' => 129.99, 'image' => $xbox_controller, 'description' => 'Play like a pro with the world’s most advanced controller.'],
    ['name' => 'DualSense Edge Wireless Controller', 'category' => 'Controller Accessories', 'price' => 199.99, 'image' => $ps_controller, 'description' => 'Get an edge in gameplay with customizable controls and swappable profiles.'],
    ['name' => 'Controller Silicone Skin Cover (Red)', 'category' => 'Controller Accessories', 'price' => 9.99, 'image' => $controller_sticks, 'description' => 'Protect your controller and improve your grip.'],
    ['name' => 'Thumb Grip Caps (Pack of 4)', 'category' => 'Controller Accessories', 'price' => 5.99, 'image' => $controller_sticks, 'description' => 'Enhance gaming experience with extra height and radius.'],

    // PC Accessories
    ['name' => 'Razer DeathAdder V2 Gaming Mouse', 'category' => 'PC Accessories', 'price' => 69.99, 'image' => $pc_mouse, 'description' => 'Ergonomic mouse with best-in-class sensor.'],
    ['name' => 'Logitech G Pro X Mechanical Keyboard', 'category' => 'PC Accessories', 'price' => 149.99, 'image' => $pc_keyboard, 'description' => 'Built for pros with swappable advanced mechanical switches.'],
    ['name' => 'SteelSeries QcK Gaming Mouse Pad', 'category' => 'PC Accessories', 'price' => 14.99, 'image' => $pc_mouse, 'description' => 'Legendary micro-woven cloth optimized for low and high DPI tracking movements.'],
    ['name' => 'HyperX Cloud II Gaming Headset', 'category' => 'PC Accessories', 'price' => 99.99, 'image' => $ps_headset, 'description' => 'Comfortable gaming headset with 7.1 surround sound.'],
    ['name' => 'Elgato Stream Deck MK.2', 'category' => 'PC Accessories', 'price' => 149.99, 'image' => $general_setup, 'description' => 'Studio controller with 15 customizable LCD keys.'],
    ['name' => 'Blue Yeti USB Microphone', 'category' => 'PC Accessories', 'price' => 129.99, 'image' => $pc_mic, 'description' => 'Professional multi-pattern USB mic for recording and streaming.'],
    ['name' => 'Razer Leviathan V2 X Soundbar', 'category' => 'PC Accessories', 'price' => 99.99, 'image' => $pc_monitor, 'description' => 'PC gaming soundbar with full-range drivers and Razer Chroma RGB.'],
    ['name' => 'Corsair MM700 RGB Extended Mouse Pad', 'category' => 'PC Accessories', 'price' => 59.99, 'image' => $pc_keyboard, 'description' => 'Extended cloth gaming mouse pad with dynamic 360° RGB lighting.'],
    ['name' => 'ASUS ROG Swift 360Hz Gaming Monitor', 'category' => 'PC Accessories', 'price' => 699.99, 'image' => $pc_monitor, 'description' => '24.5-inch esports professional gaming monitor.'],

    // Xbox
    ['name' => 'Xbox Wireless Headset', 'category' => 'Xbox', 'price' => 99.99, 'image' => $xbox_controller, 'description' => 'Experience high quality audio with a low-latency, 100% wireless connection to your Xbox console.'],
    ['name' => 'Seagate Storage Expansion Card (1TB)', 'category' => 'Xbox', 'price' => 219.99, 'image' => $xbox_controller, 'description' => 'Instantly expand the capacity of the most-powerful gaming experience Xbox has ever created.'],
    ['name' => 'Xbox Rechargeable Battery + USB-C', 'category' => 'Xbox', 'price' => 24.99, 'image' => $xbox_controller, 'description' => 'Keep the action going with the Xbox Rechargeable Battery + USB-C Cable.'],
    ['name' => 'Xbox Stereo Headset', 'category' => 'Xbox', 'price' => 59.99, 'image' => $xbox_controller, 'description' => 'Game loud and clear with the Xbox Stereo Headset.'],
    ['name' => 'Turtle Beach Stealth 600 Gen 2 MAX', 'category' => 'Xbox', 'price' => 119.99, 'image' => $ps_headset, 'description' => 'Wireless multiplatform gaming headset for Xbox.'],
    ['name' => 'Xbox Adaptive Controller', 'category' => 'Xbox', 'price' => 99.99, 'image' => $xbox_controller, 'description' => 'Designed primarily to meet the needs of gamers with limited mobility.'],
];

$stmt = $pdo->prepare("INSERT INTO products (name, category, price, image, description) VALUES (:name, :category, :price, :image, :description)");

foreach ($products as $p) {
    $stmt->execute([
        ':name' => $p['name'],
        ':category' => $p['category'],
        ':price' => $p['price'],
        ':image' => $p['image'],
        ':description' => $p['description']
    ]);
}

echo "Database seeded successfully with " . count($products) . " products.\n";
