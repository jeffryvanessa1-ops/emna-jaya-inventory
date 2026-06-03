USE mn_jaya_inventory;

INSERT INTO roles (id, name) VALUES
(1, 'Admin'),
(2, 'Manajer'),
(3, 'Kasir');

-- Password bawaan untuk semua pengguna: password
INSERT INTO users (role_id, name, username, password_hash) VALUES
(1, 'Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi'),
(2, 'Manajer Gudang', 'manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi'),
(3, 'Kasir Satu', 'cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi');

INSERT INTO categories (name, description) VALUES
('Minuman', 'Minuman dan produk botol'),
('Makanan Ringan', 'Produk makanan ringan kemasan'),
('Rumah Tangga', 'Kebutuhan rumah tangga harian');

INSERT INTO suppliers (name, phone, email, address) VALUES
('PT Sumber Grosir', '081234567890', 'sales@sumbergrosir.test', 'Jakarta'),
('CV Mitra Niaga', '082233445566', 'orders@mitraniaga.test', 'Bandung');

INSERT INTO customers (name, phone, address, price_level, credit_limit) VALUES
('Pelanggan Umum', '', '', 'retail', 0),
('Toko Berkah', '08111111111', 'Jl. Raya Pasar No. 12', 'wholesale', 5000000),
('Agen Makmur', '08222222222', 'Komplek Niaga Blok A', 'agent', 10000000);

INSERT INTO products
(category_id, sku, barcode, name, base_unit, unit_1_name, unit_1_qty, unit_2_name, unit_2_qty, cost_price, retail_price, wholesale_price, agent_price, stock_qty, min_stock)
VALUES
(1, 'BV-AQUA-600', '8991002101010', 'Air Mineral 600ml', 'PCS', 'Dus', 24, 'Karton', 48, 2500, 3500, 3200, 3000, 240, 48),
(2, 'SN-CHIP-001', '8992003301011', 'Keripik Kentang 60g', 'PCS', 'Dus', 12, 'Karton', 36, 5500, 8000, 7600, 7200, 120, 24),
(3, 'HH-SOAP-001', '8993004401012', 'Sabun Cuci 800g', 'PCS', 'Dus', 12, 'Karton', 24, 12000, 16000, 15000, 14500, 72, 12);

INSERT INTO settings (setting_key, setting_value) VALUES
('company_name', 'MN. JAYA'),
('company_address', 'Gudang Lokal'),
('company_phone', '000-0000-0000'),
('currency', 'Rp'),
('receipt_width', '80'),
('invoice_prefix', 'INV');
