<?php
require __DIR__ . '/../config.php';
require_admin();

$tab = $_GET['tab'] ?? 'products';
$msg = '';

if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='create'){
  $name       = trim($_POST['name'] ?? '');
  $team       = trim($_POST['team'] ?? '');
  $price_mad  = (int)($_POST['price_mad'] ?? 0);
  $sizes      = trim($_POST['sizes'] ?? 'S,M,L,XL');
  $tag        = trim($_POST['tag'] ?? '');
  $image_url  = trim($_POST['image_url'] ?? '');
  $rating     = (float)($_POST['rating'] ?? 0);

  $stmt = $db->prepare('INSERT INTO products(name, team, price_mad, sizes, tag, image_url, rating) VALUES(?,?,?,?,?,?,?)');
  $stmt->bind_param('ssisssd', $name, $team, $price_mad, $sizes, $tag, $image_url, $rating);
  $stmt->execute();
  $msg = 'Produit créé';
}

if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='update'){
  $id         = (int)($_POST['id'] ?? 0);
  $name       = trim($_POST['name'] ?? '');
  $team       = trim($_POST['team'] ?? '');
  $price_mad  = (int)($_POST['price_mad'] ?? 0);
  $sizes      = trim($_POST['sizes'] ?? 'S,M,L,XL');
  $tag        = trim($_POST['tag'] ?? '');
  $image_url  = trim($_POST['image_url'] ?? '');
  $rating     = (float)($_POST['rating'] ?? 0);

  $stmt = $db->prepare('UPDATE products SET name=?, team=?, price_mad=?, sizes=?, tag=?, image_url=?, rating=? WHERE id=?');
  $stmt->bind_param('ssisssdi', $name, $team, $price_mad, $sizes, $tag, $image_url, $rating, $id);
  $stmt->execute();
  $msg = 'Produit mis à jour';
}

if (($_GET['action'] ?? '')==='delete'){
  $id = (int)($_GET['id'] ?? 0);
  $db->query('DELETE FROM products WHERE id='.$id);
  $msg = 'Produit supprimé';
}

if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='status'){
  $id = (int)($_POST['id'] ?? 0);
  $st = trim($_POST['status'] ?? 'new');
  $stmt = $db->prepare('UPDATE orders SET status=? WHERE id=?');
  $stmt->bind_param('si', $st, $id);
  $stmt->execute();
  $msg = 'Statut commande mis à jour';
}

$products = db_all('SELECT * FROM products ORDER BY created_at DESC');
$orders   = db_all('SELECT * FROM orders ORDER BY created_at DESC');

$edit = null;
if (($id = (int)($_GET['edit'] ?? 0))) {
  $edit = db_one('SELECT * FROM products WHERE id='.$id);
}
?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard — Foot Tees</title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;margin:0;color:#111}
  .container{max-width:1100px;margin:0 auto;padding:16px}
  nav a{margin-right:12px}
  .card{background:#fff;border:1px solid #e6e8ef;border-radius:12px;padding:12px;margin:12px 0}
  table{width:100%;border-collapse:collapse}
  th,td{border:1px solid #e6e8ef;padding:8px;text-align:left;font-size:14px}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  .field{margin:8px 0}
  .input, .select{width:100%;padding:8px;border:1px solid #e6e8ef;border-radius:8px}
  .btn{padding:8px 12px;border:1px solid #e6e8ef;background:#fff;border-radius:999px;cursor:pointer}
  .btn--primary{background:#111;color:#fff;border-color:#111}
</style>
</head><body>


  <div class="container">
    <header style="display:flex;justify-content:space-between;align-items:center">
      <h1>Dashboard Foot Tees</h1>
      <div>
        <a class="btn" href="../index.php" target="_blank">Voir la boutique</a>
        <a class="btn" href="../logout.php">Se déconnecter</a>
      </div>
    </header>

    <nav>
      <a class="btn<?= $tab==='products'?' btn--primary':'' ?>" href="?tab=products">Produits</a>
      <a class="btn<?= $tab==='orders'?' btn--primary':'' ?>" href="?tab=orders">Commandes</a>
    </nav>

    <?php if ($msg): ?><div class="card" style="background:#ecfeff;">✅ <?= h($msg) ?></div><?php endif; ?>

    <?php if ($tab==='products'): ?>
      <div class="grid">
        <form class="card" method="post">
          <h3><?= $edit? 'Modifier produit #'.(int)$edit['id'] : 'Ajouter un produit' ?></h3>
          <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int)$edit['id'] ?>"><?php endif; ?>
          <div class="field"><label>Nom</label><input class="input" name="name" value="<?= h($edit['name']??'') ?>" required></div>
          <div class="field"><label>Équipe</label><input class="input" name="team" value="<?= h($edit['team']??'') ?>"></div>
          <div class="field"><label>Prix (MAD)</label><input class="input" type="number" name="price_mad" value="<?= h($edit['price_mad']??'') ?>" required></div>
          <div class="field"><label>Tailles (séparées par des virgules)</label><input class="input" name="sizes" value="<?= h($edit['sizes']??'S,M,L,XL') ?>" required></div>
          <div class="field"><label>Tag (ex: Promo, Hot)</label><input class="input" name="tag" value="<?= h($edit['tag']??'') ?>"></div>
          <div class="field"><label>URL image</label><input class="input" name="image_url" value="<?= h($edit['image_url']??'') ?>"></div>
          <div class="field"><label>Note ⭐</label><input class="input" type="number" step="0.1" name="rating" value="<?= h($edit['rating']??'4.5') ?>"></div>
          <button class="btn btn--primary" type="submit" name="action" value="<?= $edit? 'update':'create' ?>"><?= $edit? 'Mettre à jour':'Créer' ?></button>
          <?php if ($edit): ?><a class="btn" href="dashboard.php?tab=products">Annuler</a><?php endif; ?>
        </form>

        <div class="card">
          <h3>Produits (<?= count($products) ?>)</h3>
          <table>
            <tr><th>#</th><th>Nom</th><th>Équipe</th><th>Prix</th><th>Tailles</th><th>Tag</th><th>Image</th><th>Note</th><th>Actions</th></tr>
            <?php foreach($products as $p): ?>
              <tr>
                <td><?= (int)$p['id'] ?></td>
                <td><?= h($p['name']) ?></td>
                <td><?= h($p['team']) ?></td>
                <td><?= (int)$p['price_mad'] ?></td>
                <td><?= h($p['sizes']) ?></td>
                <td><?= h($p['tag']) ?></td>
                <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= h($p['image_url']) ?></td>
                <td><?= h($p['rating']) ?></td>
                <td>
                  <a class="btn" href="?tab=products&edit=<?= (int)$p['id'] ?>">Éditer</a>
                  <a class="btn" href="?tab=products&action=delete&id=<?= (int)$p['id'] ?>" onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    <?php else: ?>
      <div class="card">
        <h3>Commandes (<?= count($orders) ?>)</h3>
        <table>
          <tr><th>#</th><th>Date</th><th>Client</th><th>Téléphone</th><th>Ville</th><th>Total</th><th>Statut</th><th>Détails</th></tr>
          <?php foreach($orders as $o): ?>
            <tr>
              <td><?= (int)$o['id'] ?></td>
              <td><?= h($o['created_at']) ?></td>
              <td><?= h($o['customer_name']) ?></td>
              <td><?= h($o['phone']) ?></td>
              <td><?= h($o['city']) ?></td>
              <td><?= (int)$o['total_mad'] ?> MAD</td>
              <td>
                <form method="post" style="display:flex; gap:6px; align-items:center;">
                  <input type="hidden" name="action" value="status">
                  <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
                  <select name="status" class="select">
                    <?php foreach(['new','confirmed','shipped','cancelled'] as $s): ?>
                      <option value="<?= $s ?>" <?= $o['status']===$s?'selected':'' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button class="btn" type="submit">OK</button>
                </form>
              </td>
              <td>
                <?php $lines = db_all('SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE order_id='.(int)$o['id']); ?>
                <?php foreach($lines as $l): ?>
                  <div>• <?= h($l['name']) ?> (<?= h($l['size']) ?>) × <?= (int)$l['qty'] ?> — <?= (int)$l['unit_price_mad'] ?> MAD</div>
                <?php endforeach; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    <?php endif; ?>
  </div>
</body></html>
