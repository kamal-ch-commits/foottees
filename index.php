<?php
require_once __DIR__.'/config.php';
$products = db_all('SELECT * FROM products ORDER BY created_at DESC');
$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'],'qty')) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Foot Tees — Boutique T‑Shirts Football</title>
  <style>
    * { box-sizing: border-box; }
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f7fb; color:#111; line-height:1.45; }
    img { max-width:100%; display:block; height:auto; }
    a { color: inherit; text-decoration: none; }
    .container { max-width:1100px; margin:0 auto; padding:0 16px; }
    .card { background:#fff; border:1px solid #e6e8ef; border-radius:14px; }
    .btn { display:inline-block; padding:10px 14px; border:1px solid #e6e8ef; background:#fff; border-radius:999px; font-size:14px; cursor:pointer; }
    .btn--primary { background:#111; color:#fff; border-color:#111; }
    .section-title { font-size:18px; font-weight:700; margin:0 0 10px; }
    .header { position:sticky; top:0; background:#ffffffcc; border-bottom:1px solid #e6e8ef; backdrop-filter: blur(8px); }
    .header-row { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 0; }
    .brand { display:flex; align-items:center; gap:10px; }
    .brand-logo { width:36px; height:36px; border-radius:12px; background:#111; color:#fff; display:grid; place-items:center; font-weight:700; }
    .brand-title { font-weight:700; }
    .brand-sub { font-size:12px; color:#666; }
    .nav { display:none; gap:18px; }
    .hero { background:#f1f3f9; padding:48px 0; }
    .hero-grid { display:grid; gap:20px; }
    .pill { display:inline-flex; align-items:center; gap:8px; border:1px solid #e6e8ef; border-radius:999px; padding:4px 10px; font-size:12px; color:#555; }
    .hero h1 { margin:10px 0 0; font-size:34px; line-height:1.1; font-weight:800; }
    .hero p { color:#666; max-width:60ch; }
    .hero-cta { display:flex; flex-wrap:wrap; gap:10px; margin-top:14px; }
    .bullets { display:grid; grid-template-columns:repeat(2,1fr); gap:8px; margin-top:14px; color:#555; font-size:14px; }
    .hero-image { position:relative; }
    .hero-image .mock { padding:8px; }
    .hero-badge { position:absolute; right:16px; bottom:-12px; background:#111; color:#fff; border-radius:12px; padding:8px 12px; font-size:13px; }
    .main { padding:26px 0 50px; }
    .layout { display:grid; gap:18px; }
    .sidebar { padding:16px; }
    .field { margin:12px 0; }
    .label { font-size:14px; font-weight:700; margin-bottom:6px; }
    .input, .select { width:100%; border:1px solid #e6e8ef; border-radius:12px; padding:10px 12px; font-size:14px; background:#fff; }
    .products { display:grid; grid-template-columns: repeat(2, 1fr); gap:14px; }
    .product { background:#fff; border:1px solid #e6e8ef; border-radius:16px; padding:12px; transition:.15s; }
    .product:hover { transform: translateY(-2px); box-shadow:0 8px 18px rgba(0,0,0,.08); }
    .thumb { background:#eef1f7; border-radius:10px; overflow:hidden; height:240px; display:flex; align-items:center; justify-content:center; }
    .meta { display:flex; justify-content:space-between; align-items:center; margin-top:8px; gap:8px; }
    .name { font-weight:700; margin:0; }
    .tag { background:#111; color:#fff; border-radius:999px; padding:4px 8px; font-size:10px; white-space:nowrap; }
    .row { display:flex; justify-content:space-between; color:#555; font-size:14px; margin-top:4px; }
    .sizes { display:flex; gap:6px; margin-top:8px; flex-wrap:wrap; }
    .footer { border-top:1px solid #e6e8ef; color:#666; font-size:14px; padding:18px 0; }
    @media (min-width:768px){ .nav{display:flex;} .hero-grid{grid-template-columns:1fr 1fr;align-items:center;} .layout{grid-template-columns:280px 1fr;} .products{grid-template-columns:repeat(3,1fr);} .hero h1{font-size:42px;} }
    @media (min-width:1024px){ .products{grid-template-columns:repeat(4,1fr);} }
  </style>
</head>
<body>
  <header class="header">
    <div class="container header-row">
      <div class="brand">
        <div class="brand-logo">FT</div>
        <div>
          <div class="brand-title">Foot Tees</div>
          <div class="brand-sub">T‑shirts officiels & fan‑made</div>
        </div>
      </div>
      <nav class="nav">
        <a href="#nouveautes">Nouveautés</a>
        <a href="#equipes">Équipes</a>
        <a href="cart.php">Panier (<?= (int)$cartCount ?>)</a>
        <a href="login.php">Admin</a>
      </nav>
      <a class="btn" href="cart.php">Panier <span style="margin-left:6px;background:#111;color:#fff;border-radius:999px;padding:4px 8px;font-size:12px;"><?= (int)$cartCount ?></span></a>
    </div>
  </header>

  <section class="hero">
    <div class="container hero-grid">
      <div>
        <span class="pill"><span style="width:8px;height:8px;background:#22c55e;border-radius:50%;display:inline-block"></span> Stock limité — livraison partout au Maroc</span>
        <h1>Ton maillot, ton équipe, <u style="text-decoration-thickness:3px; text-underline-offset:5px;">ton style</u>.</h1>
        <p>Qualité premium, retours 7 jours. Paiement CMI, PayPal ou cash à la livraison.</p>
        <div class="hero-cta">
          <a class="btn btn--primary" href="#catalogue">Voir le catalogue</a>
          <a class="btn" href="#equipes">Choisir une équipe</a>
        </div>
        <div class="bullets"><div>• SSL & paiements sécurisés</div><div>• Livraison 24–72h</div><div>• WhatsApp</div><div>• Retours 7 jours</div></div>
      </div>
      <div class="hero-image">
        <div class="card mock"><img alt="Mockup maillot" src="assets/images/hero.jpg"></div>
        <div class="hero-badge">-15% avec le code <strong>KICKOFF15</strong></div>
      </div>
    </div>
  </section>

  <main class="main container" id="catalogue">
    <div class="layout">
      <aside class="card sidebar" id="equipes">
        <div class="field"><div class="label">Recherche (client)</div><input class="input" type="search" id="q" placeholder="Nom du produit…"></div>
        <div class="field"><div class="label">Trier</div>
          <select class="select" id="sort">
            <option value="featured">Mise en avant</option>
            <option value="price-asc">Prix: bas → haut</option>
            <option value="price-desc">Prix: haut → bas</option>
            <option value="rating-desc">Meilleures notes</option>
            <option value="name-asc">Nom A‑Z</option>
          </select>
        </div>
      </aside>

      <section>
        <h2 class="section-title" id="nouveautes">Nouveautés</h2>
        <div class="products" id="grid">
          <?php foreach($products as $p): ?>
            <article class="product" data-name="<?= h($p['name']) ?>" data-price="<?= (int)$p['price_mad'] ?>" data-rating="<?= (float)$p['rating'] ?>">
              <div class="thumb"><img alt="<?= h($p['name']) ?>" src="<?= h($p['image_url'] ?: 'assets/images/hero.jpg') ?>"></div>
              <div class="meta">
                <h3 class="name"><?= h($p['name']) ?></h3>
                <?php if ($p['tag']): ?><span class="tag"><?= h($p['tag']) ?></span><?php endif; ?>
              </div>
              <div class="row"><span><?= (int)$p['price_mad'] ?> MAD</span><span>⭐ <?= number_format((float)$p['rating'],1) ?></span></div>
              <form class="sizes" method="post" action="cart.php">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                <label> Taille
                  <select name="size" class="select" required>
                    <?php foreach(explode(',', $p['sizes']) as $s): $s=trim($s); if($s==='') continue; ?>
                      <option value="<?= h($s) ?>"><?= h($s) ?></option>
                    <?php endforeach; ?>
                  </select>
                </label>
                <label> Qté
                  <select name="qty" class="select">
                    <?php for($i=1;$i<=5;$i++): ?><option><?= $i ?></option><?php endfor; ?>
                  </select>
                </label>
                <button class="btn btn--primary" type="submit">Ajouter au panier</button>
              </form>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
  </main>

  <footer class="footer" id="contact">
    <div class="container">
      <div>&copy; <?= date('Y') ?> Foot Tees. Tous droits réservés </div>
      <div style="margin-top:6px;">WhatsApp : <a href="tel:+212612345678">+212 6 12 34 56 78</a> · Email : <a href="mailto:contact@foottees.ma">contact@foottees.ma</a></div>
    </div>
  </footer>

  <script>
    const grid = document.getElementById('grid');
    const cards = Array.from(grid.children);
    const q = document.getElementById('q');
    const sort = document.getElementById('sort');
    function render(){
      let list = cards.slice();
      const term = (q.value||'').toLowerCase();
      if (term) list = list.filter(c=>c.dataset.name.toLowerCase().includes(term));
      switch (sort.value){
        case 'price-asc': list.sort((a,b)=>a.dataset.price-b.dataset.price);break;
        case 'price-desc': list.sort((a,b)=>b.dataset.price-a.dataset.price);break;
        case 'rating-desc': list.sort((a,b)=>b.dataset.rating-a.dataset.rating);break;
        case 'name-asc': list.sort((a,b)=>a.dataset.name.localeCompare(b.dataset.name,'fr'));break;
        default: ;
      }
      grid.innerHTML=''; list.forEach(c=>grid.appendChild(c));
    }
    q.addEventListener('input',render); sort.addEventListener('change',render);
  </script>
</body>
</html>