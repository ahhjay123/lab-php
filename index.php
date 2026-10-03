<?php
require 'includes/auth.php';
$title = 'Dashboard';

$stats = $pdo->query("SELECT COUNT(*) total,
  COALESCE(SUM(quantity*price),0) value,
  SUM(quantity = 0) out_stock,
  SUM(quantity > 0 AND quantity <= reorder_level) low_stock
  FROM products")->fetch();
$low = $pdo->query("SELECT p.*, c.name category FROM products p
  LEFT JOIN categories c ON c.id = p.category_id
  WHERE p.quantity <= p.reorder_level ORDER BY p.quantity ASC LIMIT 10")->fetchAll();

include 'includes/header.php';
?>
<h1 class="h3 mb-4">Dashboard</h1>
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3"><div class="card stat-card shadow-sm"><div class="card-body">
    <div class="text-muted small">Products</div><div class="fs-3 fw-semibold"><?= (int)$stats['total'] ?></div></div></div></div>
  <div class="col-6 col-lg-3"><div class="card stat-card ok shadow-sm"><div class="card-body">
    <div class="text-muted small">Stock value</div><div class="fs-3 fw-semibold">₱<?= number_format($stats['value'], 2) ?></div></div></div></div>
  <div class="col-6 col-lg-3"><div class="card stat-card warn shadow-sm"><div class="card-body">
    <div class="text-muted small">Low stock</div><div class="fs-3 fw-semibold"><?= (int)$stats['low_stock'] ?></div></div></div></div>
  <div class="col-6 col-lg-3"><div class="card stat-card danger shadow-sm"><div class="card-body">
    <div class="text-muted small">Out of stock</div><div class="fs-3 fw-semibold"><?= (int)$stats['out_stock'] ?></div></div></div></div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <strong>Needs restocking</strong>
    <a href="product_form.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add product</a>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light"><tr><th>SKU</th><th>Product</th><th>Category</th><th class="text-end">In stock</th><th class="text-end">Reorder at</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($low as $p): ?>
        <tr>
          <td><?= e($p['sku']) ?></td><td><?= e($p['name']) ?></td><td><?= e($p['category'] ?? '—') ?></td>
          <td class="text-end"><span class="badge text-bg-<?= $p['quantity']==0?'danger':'warning' ?>"><?= (int)$p['quantity'] ?></span></td>
          <td class="text-end"><?= (int)$p['reorder_level'] ?></td>
          <td class="text-end"><a href="product_form.php?id=<?= $p['id'] ?>" class="btn btn-outline-secondary btn-sm">Update stock</a></td>
        </tr>
      <?php endforeach; if (!$low): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">Everything is stocked above its reorder level.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
