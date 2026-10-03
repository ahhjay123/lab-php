<?php
$page = basename($_SERVER['PHP_SELF']);
$flash = flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Stockroom') ?> | Stockroom</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body { background:#f4f6f8; }
    .navbar-brand { font-weight:700; letter-spacing:.2px; }
    .stat-card { border:0; border-left:5px solid var(--bs-primary); }
    .stat-card.warn { border-left-color: var(--bs-warning); }
    .stat-card.danger { border-left-color: var(--bs-danger); }
    .stat-card.ok { border-left-color: var(--bs-success); }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-box-seam me-2"></i>Stockroom</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link <?= $page=='index.php'?'active':'' ?>" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?= in_array($page,['products.php','product_form.php'])?'active':'' ?>" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link <?= $page=='categories.php'?'active':'' ?>" href="categories.php">Categories</a></li>
      </ul>
      <span class="navbar-text me-3">Hi, <?= e($_SESSION['full_name']) ?></span>
      <a class="btn btn-outline-light btn-sm" href="logout.php">Log out</a>
    </div>
  </div>
</nav>
<main class="container pb-5">
<?php if ($flash): ?>
  <div class="alert alert-<?= e($flash[1]) ?> alert-dismissible fade show">
    <?= e($flash[0]) ?><button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>
