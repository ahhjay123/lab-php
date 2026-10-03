<?php
require 'includes/auth.php';
$title = 'Categories';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'add') {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') flash('Enter a category name.', 'danger');
        else {
            try { $pdo->prepare('INSERT INTO categories (name) VALUES (?)')->execute([$name]); flash('Category added.'); }
            catch (PDOException $e) { flash('That category already exists.', 'danger'); }
        }
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $pdo->prepare('DELETE FROM categories WHERE id = ?')->execute([(int)$_POST['id']]);
        flash('Category deleted. Its products are now uncategorized.');
    }
    header('Location: categories.php'); exit;
}
$cats = $pdo->query("SELECT c.*, COUNT(p.id) products FROM categories c LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id ORDER BY c.name")->fetchAll();
include 'includes/header.php';
?>
<h1 class="h3 mb-3">Categories</h1>
<div class="row g-4">
  <div class="col-md-5">
    <div class="card shadow-sm border-0"><div class="card-body">
      <form method="post">
        <?= csrf_field() ?><input type="hidden" name="action" value="add">
        <label class="form-label">New category</label>
        <div class="input-group"><input name="name" class="form-control" required><button class="btn btn-primary">Add category</button></div>
      </form>
    </div></div>
  </div>
  <div class="col-md-7">
    <div class="card shadow-sm border-0"><ul class="list-group list-group-flush">
      <?php foreach ($cats as $c): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span><?= e($c['name']) ?> <span class="badge text-bg-secondary ms-1"><?= (int)$c['products'] ?></span></span>
          <form method="post" onsubmit="return confirm('Delete this category?')">
            <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $c['id'] ?>">
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
          </form>
        </li>
      <?php endforeach; if (!$cats): ?><li class="list-group-item text-muted">No categories yet. Add your first one.</li><?php endif; ?>
    </ul></div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
