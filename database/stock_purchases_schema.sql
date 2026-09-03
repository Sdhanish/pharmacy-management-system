-- Prompt 6: Stock Purchases Database Table & Initial Seed
USE `pharmacy_db`;

CREATE TABLE IF NOT EXISTS `stock_purchases` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `medicine_id` INT UNSIGNED NOT NULL,
  `supplier_id` INT UNSIGNED NOT NULL,
  `quantity` INT NOT NULL,
  `purchase_price` DECIMAL(10,2) NOT NULL,
  `purchase_date` DATE NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_stock_purchases_medicine` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_stock_purchases_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed initial stock purchase transactions
INSERT INTO `stock_purchases` (`id`, `medicine_id`, `supplier_id`, `quantity`, `purchase_price`, `purchase_date`, `notes`, `created_at`) VALUES
(1, 1, 1, 100, 6.50, '2026-08-15', 'Regular monthly restock order from MedSupply', DATE_SUB(NOW(), INTERVAL 15 DAY)),
(2, 2, 2, 50,  2.00, '2026-08-18', 'Emergency batch of Paracetamol Extra tablets', DATE_SUB(NOW(), INTERVAL 12 DAY)),
(3, 3, 2, 40,  12.00, '2026-08-20', 'Cardiovascular inventory restock from Novartis', DATE_SUB(NOW(), INTERVAL 10 DAY)),
(4, 4, 3, 150, 3.00, '2026-08-22', 'Seasonal allergy allergy antihistamine bulk order', DATE_SUB(NOW(), INTERVAL 8 DAY)),
(5, 6, 4, 60,  4.00, '2026-08-25', 'Pain relief NSAID shipment from Sun Pharma', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(6, 8, 4, 50,  5.00, '2026-08-28', 'Immune multivitamin restock shipment', DATE_SUB(NOW(), INTERVAL 2 DAY))
ON DUPLICATE KEY UPDATE `quantity`=VALUES(`quantity`);
