<?php
require 'includes/auth.php';
$id = (int)($_GET['id'] ?? 0);
$p = ['sku'=>'','name'=>'','category_id'=>'','price'=>'','quantity'=>0,'reorder_level'=>5];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?'); $stmt->execute([$id]);
    $p = $stmt->fetch();
    if (!$p) { flash('Product not found.', 'danger'); header('Location: products.php'); exit; }
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $p = [
      'sku' => trim($_POST['sku'] ?? ''), 'name' => trim($_POST['name'] ?? ''),
      'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
      'price' => $_POST['price'] ?? '', 'quantity' => $_POST['quantity'] ?? '',
      'reorder_level' => $_POST['reorder_level'] ?? '',
    ];
    if ($p['sku'] === '' || $p['name'] === '') $errors[] = 'SKU and name are required.';
    if (!is_numeric($p['price']) || $p['price'] < 0) $errors[] = 'Enter a valid price.';
    if (!ctype_digit((string)$p['quantity']) || !ctype_digit((string)$p['reorder_level'])) $errors[] = 'Quantity and reorder level must be whole numbers.';
    if (!$errors) {
        try {
            if ($id) {
                $pdo->prepare('UPDATE products SET sku=?, name=?, category_id=?, price=?, quantity=?, reorder_level=? WHERE id=?')
                    ->execute([$p['sku'],$p['name'],$p['category_id'],$p['price'],$p['quantity'],$p['reorder_level'],$id]);
                flash('Product updated.');
            } else {
                $pdo->prepare('INSERT INTO products (sku,name,category_id,price,quantity,reorder_level) VALUES (?,?,?,?,?,?)')
                    ->execute([$p['sku'],$p['name'],$p['category_id'],$p['price'],$p['quantity'],$p['reorder_level']]);
                flash('Product added.');
            }
            header('Location: products.php'); exit;
        } catch (PDOException $e) {
            $errors[] = $e->getCode() == 23000 ? 'That SKU is already in use.' : 'Could not save the product.';
        }
    }
}
$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$title = $id ? 'Edit product' : 'Add product';
include 'includes/header.php';
?>
<h1 class="h3 mb-3"><?= e($title) ?></h1>
<?php foreach ($errors as $err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endforeach; ?>
<div class="card shadow-sm border-0" style="max-width:720px"><div class="card-body">
<form method="post">
  <?= csrf_field() ?>
  <div class="row g-3">
    <div class="col-md-4"><label class="form-label">SKU</label><input name="sku" class="form-control" value="<?= e($p['sku']) ?>" required></div>
    <div class="col-md-8"><label class="form-label">Product name</label><input name="name" class="form-control" value="<?= e($p['name']) ?>" required></div>
    <div class="col-md-6"><label class="form-label">Category</label>
      <select name="category_id" class="form-select">
        <option value="">None</option>
        <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $p['category_id']==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
      </select></div>
    <div class="col-md-6"><label class="form-label">Price (₱)</label><input name="price" type="number" step="0.01" min="0" class="form-control" value="<?= e($p['price']) ?>" required></div>
    <div class="col-md-6"><label class="form-label">Quantity in stock</label><input name="quantity" type="number" min="0" class="form-control" value="<?= e($p['quantity']) ?>" required></div>
    <div class="col-md-6"><label class="form-label">Reorder level</label><input name="reorder_level" type="number" min="0" class="form-control" value="<?= e($p['reorder_level']) ?>" required>
      <div class="form-text">Flag as low when stock reaches this number.</div></div>
  </div>
  <div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary">Save product</button>
    <a href="products.php" class="btn btn-outline-secondary">Cancel</a>
  </div>
</form>
</div></div>
<?php include 'includes/footer.php'; ?>
