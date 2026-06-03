<?php
require_once dirname(__DIR__) . '/config/database.php';

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function money(float|int|string $value): string
{
    return 'Rp ' . number_format((float)$value, 0, ',', '.');
}

function redirect(string $path): never
{
    header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flashes(): array
{
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $items;
}

function log_activity(string $activity): void
{
    try {
        db()->prepare("
            INSERT INTO activity_logs
            (
                user_id,
                activity
            )
            VALUES
            (
                ?,
                ?
            )
        ")->execute([
            $_SESSION['user']['id'] ?? null,
            $activity
        ]);
    } catch (Throwable $e) {
        // ignore logging errors
    }
}

function next_invoice_no(PDO $pdo, string $table = 'sales', string $prefix = 'INV'): string
{
    $date = date('Ymd');
    $like = $prefix . '-' . $date . '-%';
    $stmt = $pdo->prepare("SELECT invoice_no FROM {$table} WHERE invoice_no LIKE ? ORDER BY invoice_no DESC LIMIT 1");
    $stmt->execute([$like]);
    $last = (string)($stmt->fetchColumn() ?: '');
    $seq = $last ? ((int)substr($last, -5) + 1) : 1;
    return sprintf('%s-%s-%05d', $prefix, $date, $seq);
}

function product_unit_multiplier(array $product, string $unit): float
{
    if ($unit === ($product['unit_1_name'] ?? null)) {
        return (float)$product['unit_1_qty'];
    }
    if ($unit === ($product['unit_2_name'] ?? null)) {
        return (float)$product['unit_2_qty'];
    }
    return 1.0;
}

function add_stock_movement(PDO $pdo, int $productId, string $type, float $qty, ?string $refType = null, ?int $refId = null, string $notes = ''): void
{
    $stmt = $pdo->prepare('SELECT stock_qty FROM products WHERE id = ? FOR UPDATE');
    $stmt->execute([$productId]);
    $previous = (float)$stmt->fetchColumn();
    $new = $previous + $qty;

    $pdo->prepare('UPDATE products SET stock_qty = ? WHERE id = ?')->execute([$new, $productId]);
    $pdo->prepare(
        'INSERT INTO stock_movements (product_id, movement_type, reference_type, reference_id, qty, previous_stock, new_stock, notes, created_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    )->execute([$productId, $type, $refType, $refId, $qty, $previous, $new, $notes, $_SESSION['user']['id'] ?? null]);
}

function selected(string $actual, string $expected): string
{
    return $actual === $expected ? 'selected' : '';
}

function price_level_label(?string $level): string
{
    return [
        'retail' => 'Eceran',
        'wholesale' => 'Grosir',
        'agent' => 'Agen',
    ][$level] ?? (string)$level;
}

function sale_status_label(?string $status): string
{
    return [
        'paid' => 'Lunas',
        'partial' => 'Sebagian',
        'credit' => 'Tempo',
        'void' => 'Batal',
    ][$status] ?? (string)$status;
}

function purchase_status_label(?string $status): string
{
    return [
        'ordered' => 'Dipesan',
        'received' => 'Diterima',
        'cancelled' => 'Dibatalkan',
    ][$status] ?? (string)$status;
}

function movement_type_label(?string $type): string
{
    return [
        'stock_in' => 'Stok Masuk',
        'stock_out' => 'Stok Keluar',
        'adjustment' => 'Penyesuaian',
        'purchase' => 'Pembelian',
        'sale' => 'Penjualan',
    ][$type] ?? (string)$type;
}

function payment_method_label(?string $method): string
{
    return [
        'cash' => 'Tunai',
        'transfer' => 'Transfer',
        'qris' => 'QRIS',
    ][$method] ?? (string)$method;
}

function setting(string $key, string $default = ''): string
{
    static $settings = null;

    if ($settings === null) {
        $settings = [];

        try {
            $rows = db()->query("
                SELECT setting_key, setting_value
                FROM settings
            ")->fetchAll();

            foreach ($rows as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Throwable $e) {
            $settings = [];
        }
    }

    return $settings[$key] ?? $default;
}

function site_url(string $path = ''): string
{
    $base = rtrim(BASE_URL, '/');

    if ($path === '') {
        return $base;
    }

    return $base . '/' . ltrim($path, '/');
}

function company_logo_url(): string
{
    $file = trim(setting('company_logo'));

    if ($file === '') {
        return '';
    }

    return site_url('uploads/' . $file);
}

function company_logo_img(
    int $height = 52,
    string $alt = APP_NAME
): string {
    $src = company_logo_url();

    if ($src === '') {
        return '';
    }

    return sprintf(
        '<img src="%s" alt="%s" style="height:%dpx;width:auto;display:block;">',
        e($src),
        e($alt),
        $height
    );
}