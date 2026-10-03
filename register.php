<?php
require_once 'config.php';
// Registration is only open while no users exist (first-run setup).
$count = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
if ($count > 0) { header('Location: login.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['full_name'] ?? '');
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($name === '' || $user === '') $error = 'Fill in all fields.';
    elseif (strlen($pass) < 8) $error = 'Password must be at least 8 characters.';
    elseif ($pass !== ($_POST['confirm'] ?? '')) $error = 'Passwords do not match.';
    else {
        $pdo->prepare('INSERT INTO users (full_name, username, password_hash) VALUES (?,?,?)')
            ->execute([$name, $user, password_hash($pass, PASSWORD_DEFAULT)]);
        flash('Account created. You can log in now.');
        header('Location: login.php'); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create admin | Stockroom</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center" style="min-height:100vh">
<div class="container" style="max-width:420px">
  <div class="card shadow border-0"><div class="card-body p-4">
    <h1 class="h4">Create the admin account</h1>
    <p class="text-muted">This page closes once the first account exists.</p>
    <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <div class="mb-3"><label class="form-label">Full name</label><input name="full_name" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Confirm password</label><input type="password" name="confirm" class="form-control" required></div>
      <button class="btn btn-primary w-100">Create account</button>
    </form>
  </div></div>
</div>
</body></html>
