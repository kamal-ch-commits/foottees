<?php 
require_once __DIR__.'/config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $stmt = $db->prepare('SELECT id,email,password_hash,role FROM users WHERE email=?');
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $u = $res->fetch_assoc();
  if ($u && password_verify($pass, $u['password_hash'])){
    $_SESSION['user'] = ['id'=>$u['id'],'email'=>$u['email'],'role'=>$u['role']];
    header('Location: admin/dashboard.php'); exit;
  } else { $err = 'Identifiants invalides'; }
}
?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin — Connexion</title>
<style>body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;margin:0;color:#111} .container{max-width:420px;margin:60px auto;padding:16px} .card{background:#fff;border:1px solid #e6e8ef;border-radius:12px;padding:16px} .field{margin:10px 0} .label{display:block;font-weight:700;margin-bottom:6px} .input{width:100%;padding:10px;border:1px solid #e6e8ef;border-radius:10px} .btn{padding:10px 14px;border:1px solid #e6e8ef;background:#fff;border-radius:999px;cursor:pointer} .btn--primary{background:#111;color:#fff;border-color:#111}</style>
</head><body>
  <div class="container">
    <form class="card" method="post">
      <h2>Connexion admin</h2>
      <?php if($err): ?><div style="color:#b91c1c;"><?= h($err) ?></div><?php endif; ?>
      <div class="field"><label class="label">Email</label><input class="input" type="email" name="email" required value="admin@foottees.ma"></div>
      <div class="field"><label class="label">Mot de passe</label><input class="input" type="password" name="password" required value="admin123"></div>
      <button class="btn btn--primary" type="submit">Se connecter</button>
      <a class="btn" href="index.php">Annuler</a>
    </form>
  </div>
</body></html>