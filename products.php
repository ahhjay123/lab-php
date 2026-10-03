<?php
require 'includes/auth.php';
$title = 'Products';

$q = trim($_GET['q'] ?? '');
$cat = (int)($_GET['cat'] ?? 0);
$sql = "SELECT p.*, c.name category FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE 1=1";
$args = [];
if ($q !== '') { $sql .= " AND (p.name LIKE ? OR p.sku LIKE ?)"; $args[] = "%$q%"; $args[] = "%$q%"; }
if ($cat) { $sql .= " AND p.category_id = ?"; $args[] = $cat; }
$sql .= " ORDER BY p.name";
$stmt = $pdo->prepare($sql); $stmt->execute($args);
$products = $stmt->fetchAll();
$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Products</h1>
  <a href="product_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add product</a>
</div>

<form class="row g-2 mb-3" method="get">
  <div class="col-md-6"><input name="q" value="<?= e($q) ?>" class="form-control" placeholder="Search by name or SKU"></div>
  <div class="col-md-4">
    <select name="cat" class="form-select">
      <option value="0">All categories</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $cat==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2 d-grid"><button class="btn btn-outline-secondary">Filter</button></div>
</form>

<div class="card shadow-sm border-0"><div class="table-responsive">
  <table class="table table-hover mb-0 align-middle">
    <thead class="table-light"><tr><th>SKU</th><th>Product</th><th>Category</th><th class="text-end">Price</th><th class="text-end">Qty</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($products as $p):
      if ($p['quantity'] == 0) { $s = ['danger','Out of stock']; }
      elseif ($p['quantity'] <= $p['reorder_level']) { $s = ['warning','Low']; }
      else { $s = ['success','In stock']; } ?>
      <tr>
        <td><?= e($p['sku']) ?></td><td><?= e($p['name']) ?></td><td><?= e($p['category'] ?? '—') ?></td>
        <td class="text-end">₱<?= number_format($p['price'], 2) ?></td>
        <td class="text-end"><?= (int)$p['quantity'] ?></td>
        <td><span class="badge text-bg-<?= $s[0] ?>"><?= $s[1] ?></span></td>
        <td class="text-end text-nowrap">
          <a href="product_form.php?id=<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
          <form method="post" action="product_delete.php" class="d-inline" onsubmit="return confirm('Delete <?= e(addslashes($p['name'])) ?>?')">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $p['id'] ?>">
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
          </form>
        </td>
      </tr>
    <?php endforeach; if (!$products): ?>
      <tr><td colspan="7" class="text-center text-muted py-4">No products match. <a href="product_form.php">Add one</a>.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div></div>
<?php include 'includes/footer.php'; ?>
