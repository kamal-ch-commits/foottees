<?php
require_once __DIR__.'/config.php';
if (!isset($_SESSION['cart']) || !$_SESSION['cart']) { header('Location: cart.php'); exit; }

$errors = []; $done = false; $orderId = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $city = trim($_POST['city'] ?? '');
  if ($name==='') $errors[]='Nom requis';
  if ($phone==='') $errors[]='Téléphone requis';
  if ($address==='') $errors[]='Adresse requise';
  if ($city==='') $errors[]='Ville requise';

  if (!$errors) {
    $total = 0; $cart = $_SESSION['cart'];
    foreach ($cart as $line) {
      $pid = (int)$line['product_id'];
      $p = db_one('SELECT price_mad FROM products WHERE id='.$pid);
      if ($p) $total += (int)$p['price_mad'] * (int)$line['qty'];
    }

    $stmt = $db->prepare('INSERT INTO orders(customer_name, phone, address, city, total_mad, status) VALUES(?,?,?,?,?,"new")');
    $stmt->bind_param('ssssi', $name, $phone, $address, $city, $total);
    $stmt->execute();
    $orderId = $db->insert_id;

    $it = $db->prepare('INSERT INTO order_items(order_id, product_id, size, qty, unit_price_mad) VALUES(?,?,?,?,?)');
    foreach ($cart as $line) {
      $pid = (int)$line['product_id'];
      $p = db_one('SELECT price_mad FROM products WHERE id='.$pid);
      if (!$p) continue;
      $price = (int)$p['price_mad'];
      $qty   = (int)$line['qty'];
      $size  = $line['size'];
      $it->bind_param('iisii', $orderId, $pid, $size, $qty, $price);
      $it->execute();
    }

    $_SESSION['cart'] = [];
    $done = true;
  }
}
?>
<!DOCTYPE html>
<html lang="fr"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Commande — Foot Tees</title>
<style>body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;margin:0;color:#111} .container{max-width:700px;margin:0 auto;padding:16px} .card{background:#fff;border:1px solid #e6e8ef;border-radius:12px;padding:16px;margin:12px 0} .field{margin:10px 0} .label{display:block;font-weight:700;margin-bottom:6px} .input{width:100%;padding:10px;border:1px solid #e6e8ef;border-radius:10px} .btn{padding:10px 14px;border:1px solid #e6e8ef;background:#fff;border-radius:999px;cursor:pointer} .btn--primary{background:#111;color:#fff;border-color:#111}</style>
</head><body>
  <div class="container">
    <h1>Finaliser la commande</h1>

    <?php if ($done): ?>
      <div class="card">
        <p>Merci ! Ta commande <strong>#<?= (int)$orderId ?></strong> a été enregistrée. Nous te contacterons par WhatsApp pour la livraison (paiement à la livraison).</p>
        <p><a class="btn" href="index.php">← Retour à la boutique</a></p>
      </div>
    <?php else: ?>
      <?php if ($errors): ?><div class="card" style="color:#b91c1c;">Erreur : <?= h(implode(', ', $errors)) ?></div><?php endif; ?>
      <form class="card" method="post">
        <div class="field"><label class="label">Nom complet</label><input class="input" name="name" required></div>
        <div class="field"><label class="label">Téléphone</label><input class="input" name="phone" required></div>
        <div class="field"><label class="label">Adresse</label><textarea class="input" name="address" rows="3" required></textarea></div>
        <div class="field"><label class="label">Ville</label><input class="input" name="city" required></div>
        <button class="btn btn--primary" type="submit">Confirmer ma commande</button>
        <a class="btn" href="cart.php">Annuler</a>
      </form>
    <?php endif; ?>
  </div>
</body></html>