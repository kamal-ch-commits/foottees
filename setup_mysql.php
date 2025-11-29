<?php
require_once __DIR__.'/config.php';

$db->query("CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'admin',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->query("CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  team VARCHAR(100),
  price_mad INT NOT NULL,
  sizes VARCHAR(100) NOT NULL,
  tag VARCHAR(50),
  image_url VARCHAR(500),
  rating DECIMAL(3,1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->query("CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(160) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  address TEXT NOT NULL,
  city VARCHAR(100) NOT NULL,
  total_mad INT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'new',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->query("CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  size VARCHAR(10) NOT NULL,
  qty INT NOT NULL,
  unit_price_mad INT NOT NULL,
  CONSTRAINT fk_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$email = 'admin@foottees.ma';
$exists = db_value("SELECT COUNT(*) FROM users WHERE email='".$db->real_escape_string($email)."'");
if (!$exists) {
  $stmt = $db->prepare('INSERT INTO users(email, password_hash, role) VALUES(?, ?, "admin")');
  $hash = password_hash('admin123', PASSWORD_BCRYPT);
  $stmt->bind_param('ss', $email, $hash);
  $stmt->execute();
}

$has = db_value('SELECT COUNT(*) FROM products');
if (!$has) {
  $demo = [
    ['Maillot Arsenal Domicile 24/25','Arsenal',299,'S,M,L,XL','Best Seller','assets/images/arsenal.jpg',4.8],
    ['T-shirt Brazil Rétro','Brésil',199,'XS,S,M,L','Nouveau','assets/images/brazil.jpg',4.5],
    ["Équipe d'Allemagne — Édition Limitée",'Allemagne',349,'M,L,XL,XXL','Hot','assets/images/germany.jpg',4.6],
    ['Manchester United 24/25','Manchester United',239,'S,M,L','Promo','assets/images/manutd.jpg',4.2],
    ['Chelsea 24/25','Chelsea',279,'S,M,L,XL,XXL','Top','assets/images/chelsea.jpg',4.9],
  ];
  $stmt = $db->prepare('INSERT INTO products(name, team, price_mad, sizes, tag, image_url, rating) VALUES(?,?,?,?,?,?,?)');
  foreach ($demo as $p){ $stmt->bind_param('ssisssd', $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6]); $stmt->execute(); }
}

echo '<h2>Base MySQL initialisée ✅</h2><p>Admin: admin@foottees.ma / admin123</p><p><a href="index.php">Aller à la boutique</a> · <a href="login.php">Panel admin</a></p>';