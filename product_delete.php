<?php
require 'includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([(int)($_POST['id'] ?? 0)]);
    flash('Product deleted.');
}
header('Location: products.php');
