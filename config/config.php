<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Jakarta');

define('APP_NAME', 'MN. JAYA');
define('BASE_URL', '/mn-jaya-inventory/public');
define('UPLOAD_DIR', dirname(__DIR__) . '/public/uploads/products');

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'mn_jaya_inventory');
define('DB_USER', 'root');
define('DB_PASS', '');

if (session_status() === PHP_SESSION_NONE) {
    session_name('MNJAYA_POS');
    session_start();
}
