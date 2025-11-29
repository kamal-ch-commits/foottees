<?php
require_once __DIR__.'/config.php';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'add') {
    $pid = (int)($_POST['product_id'] ?? 0);
    $size = trim($_POST['size'] ?? '');
    $qty  = max(1, (int)($_POST['qty'] ?? 1));
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['product_id']===$pid && $item['size']===$size) { $item['qty'] += $qty; $found = true; break; }
    }
    unset($item);
    if (!$found) $_SESSION['cart'][] = ['product_id'=>$pid,'size'=>$size,'qty'=>$qty];
    header('Location: cart.php'); exit;
  }
  if ($action === 'update') {
    $idx = (int)($_POST['idx'] ?? -1);
    $qty = max(0, (int)($_POST['qty'] ?? 1));
    if (isset($_SESSION['cart'][$idx])) {
      if ($qty===0) unset($_SESSION['cart'][$idx]); else $_SESSION['cart'][$idx]['qty']=$qty;
    }
    header('Location: cart.php'); exit;
  }
  if ($action === 'clear') { $_SESSION['cart'] = []; header('Location: cart.php'); exit; }
}

$total = 0; $items = [];
foreach ($_SESSION['cart'] as $i => $line) {
  $pid = (int)$line['product_id'];
  $p = db_one('SELECT id,name,price_mad,image_url FROM products WHERE id='.$pid);
  if ($p) {
    $lineTotal = (int)$p['price_mad'] * (int)$line['qty'];
    $total += $lineTotal;
    $items[] = ['idx'=>$i,'id'=>$pid,'name'=>$p['name'],'price'=>(int)$p['price_mad'],'image'=>$p['image_url'],'size'=>$line['size'],'qty'=>(int)$line['qty'],'line_total'=>$lineTotal];
  }
}
?>
<!DOCTYPE html>
<html lang="fr"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Panier — Foot Tees</title>
<style>body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;margin:0;color:#111} .container{max-width:900px;margin:0 auto;padding:16px} .card{background:#fff;border:1px solid #e6e8ef;border-radius:12px;padding:12px;margin:12px 0} .row{display:flex;align-items:center;gap:12px} img{width:80px;height:80px;object-fit:cover;border-radius:8px} .right{margin-left:auto;color:#555} .btn{padding:8px 12px;border:1px solid #e6e8ef;background:#fff;border-radius:999px;cursor:pointer} .btn--primary{background:#111;color:#fff;border-color:#111} .top{display:flex;justify-content:space-between;align-items:center} a{text-decoration:none;color:inherit}</style>
</head><body>
  <div class="container">
    <div class="top"><h1>🛒 Ton panier</h1><p><a href="index.php" class="btn">← Continuer mes achats</a></p></div>

    <?php if (!$items): ?>
      <div class="card">Ton panier est vide.</div>
    <?php else: ?>
      <?php foreach($items as $it): ?>
        <div class="card row">
          <img src="<?= h($it['image']) ?>" alt="<?= h($it['name']) ?>">
          <div>
            <div><strong><?= h($it['name']) ?></strong></div>
            <div>Taille: <?= h($it['size']) ?></div>
            <div><?= (int)$it['price'] ?> MAD</div>
          </div>
          <form method="post" class="right">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="idx" value="<?= (int)$it['idx'] ?>">
            Qté <input type="number" name="qty" min="0" value="<?= (int)$it['qty'] ?>" style="width:70px">
            <button class="btn" type="submit">Mettre à jour</button>
          </form>
          <div class="right"><strong><?= (int)$it['line_total'] ?> MAD</strong></div>
        </div>
      <?php endforeach; ?>

      <div class="card" style="display:flex;justify-content:space-between;align-items:center;">
        <form method="post"><input type="hidden" name="action" value="clear"><button class="btn" type="submit">Vider le panier</button></form>
        <div><strong>Total: <?= (int)$total ?> MAD</strong> <a class="btn btn--primary" href="checkout.php">Passer la commande</a></div>
      </div>
    <?php endif; ?>
  </div>
</body></html>