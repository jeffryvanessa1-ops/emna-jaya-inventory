<?php

require_once __DIR__ . '/auth.php';

function page_header(string $title): void
{
    $user = current_user();

    $nav = [];

    // Everyone
    $nav[] = ['dashboard.php', 'Dasbor', 'bi-speedometer2'];
    $nav[] = ['pos.php', 'POS', 'bi-receipt'];
    $nav[] = ['sales.php', 'Daftar Transaksi', 'bi-journal-text'];
    $nav[] = ['customers.php', 'Pelanggan', 'bi-people'];
    $nav[] = ['receivables.php', 'Buku Piutang', 'bi-journal-bookmark'];
    

    // Admin & Manajer
    if (has_role(['Manajer'])) {

        $nav[] = ['products.php', 'Produk', 'bi-box-seam'];
        $nav[] = ['inventory.php', 'Inventori', 'bi-arrow-left-right'];
        $nav[] = ['suppliers.php', 'Pemasok', 'bi-truck'];
        $nav[] = ['purchases.php', 'Pembelian', 'bi-bag-plus'];
        $nav[] = ['payments.php', 'Pembayaran', 'bi-cash'];
        $nav[] = ['reports.php', 'Laporan', 'bi-graph-up'];

    }

    // Admin only
    if (($user['role'] ?? '') === 'Admin') {

        $nav[] = ['users.php', 'Pengguna', 'bi-person-gear'];
        $nav[] = ['settings.php', 'Pengaturan', 'bi-gear'];;

    }

?>

<!doctype html>

<html lang="id">
<head>

<meta charset="utf-8">

<meta name="viewport"
   content="width=device-width, initial-scale=1">

<title><?= e($title) ?> - <?= APP_NAME ?></title>

<link href="assets/vendor/bootstrap.min.css"
      rel="stylesheet">

<link href="assets/css/app.css"
      rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet"
href="assets/vendor/bootstrap-icons/font/bootstrap-icons.css">

</head>

<body>

<div class="app-shell">

<aside class="sidebar">

<div class="brand">

<span class="brand-mark">
MN
</span>

<div>

<strong>
MN. JAYA
</strong>

<small>
POS Gudang
</small>

</div>

</div>

<nav>

<div class="menu-group">
<div class="menu-title">
<span>📦 PENJUALAN</span>

</div>

<a href="pos.php" class="<?= basename($_SERVER['PHP_SELF'])=='pos.php'?'active':'' ?>">
<i class="bi bi-receipt"></i>
<span>POS</span>
</a>

<a href="sales.php" class="<?= basename($_SERVER['PHP_SELF'])=='sales.php'?'active':'' ?>">
<i class="bi bi-journal-text"></i>
<span>Daftar Transaksi</span>
</a>

<a href="customers.php" class="<?= basename($_SERVER['PHP_SELF'])=='customers.php'?'active':'' ?>">
<i class="bi bi-people"></i>
<span>Pelanggan</span>
</a>

<a href="receivables.php" class="<?= basename($_SERVER['PHP_SELF'])=='receivables.php'?'active':'' ?>">
<i class="bi bi-journal-bookmark"></i>
<span>Buku Piutang</span>
</a>


</div>

<?php if (has_role(['Manajer'])): ?>

<div class="menu-group">
<div class="menu-title" onclick="this.parentNode.classList.toggle('collapsed')">
📋 INVENTORI
</div>

<a href="products.php" class="<?= basename($_SERVER['PHP_SELF'])=='products.php'?'active':'' ?>">
<i class="bi bi-box-seam"></i>
<span>Produk</span>
</a>

<a href="inventory.php" class="<?= basename($_SERVER['PHP_SELF'])=='inventory.php'?'active':'' ?>">
<i class="bi bi-arrow-left-right"></i>
<span>Inventori</span>
</a>

<a href="suppliers.php" class="<?= basename($_SERVER['PHP_SELF'])=='suppliers.php'?'active':'' ?>">
<i class="bi bi-truck"></i>
<span>Pemasok</span>
</a>

<a href="purchases.php" class="<?= basename($_SERVER['PHP_SELF'])=='purchases.php'?'active':'' ?>">
<i class="bi bi-bag-plus"></i>
<span>Pembelian</span>
</a>

<a href="payments.php" class="<?= basename($_SERVER['PHP_SELF'])=='payments.php'?'active':'' ?>">
<i class="bi bi-cash"></i>
<span>Pembayaran</span>
</a>

</div>

<div class="menu-group">
<div class="menu-title" onclick="this.parentNode.classList.toggle('collapsed')">
📈 LAPORAN
</div>

<a href="reports.php" class="<?= basename($_SERVER['PHP_SELF'])=='reports.php'?'active':'' ?>">
<i class="bi bi-graph-up"></i>
<span>Laporan</span>
</a>

</div>

<?php endif; ?>

<?php if (($user['role'] ?? '') === 'Admin'): ?>

<div class="menu-group">
<div class="menu-title" onclick="this.parentNode.classList.toggle('collapsed')">
⚙ ADMIN
</div>

<a href="users.php" class="<?= basename($_SERVER['PHP_SELF'])=='users.php'?'active':'' ?>">
<i class="bi bi-person-gear"></i>
<span>Pengguna</span>
</a>

<a href="activity_logs.php" class="<?= basename($_SERVER['PHP_SELF'])=='activity_logs.php'?'active':'' ?>">
<i class="bi bi-clock-history"></i>
<span>Audit Log</span>
</a>

<a href="backup.php" class="<?= basename($_SERVER['PHP_SELF'])=='backup.php'?'active':'' ?>">
<i class="bi bi-database-down"></i>
<span>Backup Database</span>
</a>

<a href="settings.php" class="<?= basename($_SERVER['PHP_SELF'])=='settings.php'?'active':'' ?>">
<i class="bi bi-gear"></i>
<span>Pengaturan</span>
</a>

</div>

<?php endif; ?>

</nav>


</aside>

<main class="content">

<header class="topbar">

<button class="btn btn-dark d-lg-none"
     id="sidebarToggle">

<i class="bi bi-list"></i>

</button>

<div>

<h1>
<?= e($title) ?>
</h1>

<span>
<?= date('l, d M Y') ?>
</span>

</div>

<div class="user-box">

<span>

<?= e($user['name'] ?? '') ?>

<small>
<?= e($user['role'] ?? '') ?>
</small>

</span>

<a class="btn btn-outline-dark btn-sm"
href="logout.php">

Keluar

</a>

</div>

</header>

<?php foreach (flashes() as $item): ?>

<div class="alert alert-<?= e($item['type']) ?> alert-dismissible fade show">

<?= e($item['message']) ?>

<button type="button"
     class="btn-close"
     data-bs-dismiss="alert"> </button>

</div>

<?php endforeach; ?>

<?php
}

function page_footer(): void
{
?>

</main>

</div>

<script src="assets/vendor/bootstrap.bundle.min.js"></script>

<script src="assets/js/app.js"></script>

<!-- DASHBOARD -->
<a href="dashboard.php">
    <i class="bi bi-speedometer2"></i>
    <span>Dasbor</span>
</a>

<!-- POS -->
<a href="pos.php">
    <i class="bi bi-receipt"></i>
    <span>POS</span>
</a>

<!-- TRANSAKSI -->
<a href="sales.php">
    <i class="bi bi-journal-text"></i>
    <span>Daftar Transaksi</span>
</a>

<!-- PELANGGAN -->
<a href="customers.php">
    <i class="bi bi-people"></i>
    <span>Pelanggan</span>
</a>

<!-- PIUTANG -->
<a href="receivables.php">
    <i class="bi bi-cash-stack"></i>
    <span>Buku Piutang</span>
</a>

<!-- PRODUK -->
<a href="products.php">
    <i class="bi bi-box-seam"></i>
    <span>Produk</span>
</a>

<!-- INVENTORI -->
<a href="inventory.php">
    <i class="bi bi-arrow-left-right"></i>
    <span>Inventori</span>
</a>

<!-- PEMASOK -->
<a href="suppliers.php">
    <i class="bi bi-truck"></i>
    <span>Pemasok</span>
</a>

<!-- PEMBELIAN -->
<a href="purchases.php">
    <i class="bi bi-bag-plus"></i>
    <span>Pembelian</span>
</a>

<!-- PEMBAYARAN -->
<a href="payments.php">
    <i class="bi bi-cash-coin"></i>
    

<script>
document.addEventListener('DOMContentLoaded', function(){

    document.querySelectorAll('.menu-title').forEach(function(title){

        title.addEventListener('click', function(){

            const group = this.parentElement;

            group.classList.toggle('collapsed');

        });

    });

});
</script>
</body>

</html>

<?php
}
?>
