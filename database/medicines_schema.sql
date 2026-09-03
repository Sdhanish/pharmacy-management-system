-- Prompt 5 Schema & Migration for Medicines Table
USE `pharmacy_db`;

-- Drop foreign keys and recreate clean medicines table
DROP TABLE IF EXISTS `sale_items`;
DROP TABLE IF EXISTS `sales`;
DROP TABLE IF EXISTS `stock_history`;
DROP TABLE IF EXISTS `stocks`;
DROP TABLE IF EXISTS `medicines`;

CREATE TABLE `medicines` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `medicine_name` VARCHAR(150) NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `supplier_id` INT UNSIGNED DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` INT NOT NULL DEFAULT 0,
  `expiry_date` DATE NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_medicines_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_medicines_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recreate stocks table for batch tracking
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `medicine_id` INT UNSIGNED NOT NULL,
  `batch_number` VARCHAR(50) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  `purchase_date` DATE DEFAULT NULL,
  `expiry_date` DATE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_stocks_medicine` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recreate stock_history table
CREATE TABLE IF NOT EXISTS `stock_history` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `medicine_id` INT UNSIGNED NOT NULL,
  `transaction_type` ENUM('PURCHASE', 'SALE', 'ADJUSTMENT', 'RETURN', 'EXPIRED') NOT NULL,
  `quantity` INT NOT NULL,
  `balance_after` INT NOT NULL,
  `reference_no` VARCHAR(100) DEFAULT NULL,
  `notes` VARCHAR(255) DEFAULT NULL,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_stock_history_medicine` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_stock_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Data with high-quality medicine images and realistic attributes
INSERT INTO `medicines` (`id`, `medicine_name`, `category_id`, `supplier_id`, `description`, `price`, `stock_quantity`, `expiry_date`, `image_url`, `status`) VALUES
(1, 'Amoxicillin 500mg Capsules', 1, 1, 'Broad-spectrum antibiotic used to treat various bacterial infections including ear, throat, and respiratory tract infections.', 12.50, 140, '2027-06-30', 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&auto=format&fit=crop&q=80', 'active'),
(2, 'Paracetamol Extra 650mg', 2, 2, 'Effective antipyretic and analgesic for fever reduction and fast relief of headaches, muscle aches, and mild pain.', 4.50, 6, '2027-12-31', 'https://images.unsplash.com/photo-1585435557343-3b092031a831?w=300&auto=format&fit=crop&q=80', 'active'),
(3, 'Atorvastatin 20mg Tablets', 3, 2, 'Statin medication that lowers LDL (bad) cholesterol and triglycerides while raising HDL (good) cholesterol in blood.', 24.50, 85, '2027-09-15', 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=300&auto=format&fit=crop&q=80', 'active'),
(4, 'Cetirizine HCl 10mg', 4, 3, 'Non-drowsy second-generation antihistamine for seasonal allergic rhinitis, hives, and itchy skin allergies.', 6.50, 210, '2027-08-20', 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=300&auto=format&fit=crop&q=80', 'active'),
(5, 'Omeprazole Delayed-Release 20mg', 6, 1, 'Proton pump inhibitor (PPI) that decreases excess stomach acid, treating acid reflux and gastroesophageal reflux disease (GERD).', 16.00, 4, '2026-09-20', 'https://images.unsplash.com/photo-1550572017-edd951aa8f72?w=300&auto=format&fit=crop&q=80', 'active'),
(6, 'Ibuprofen Rapid 400mg', 2, 4, 'Fast-acting NSAID for targeted inflammatory pain, menstrual cramps, dental pain, and arthritis symptoms.', 8.00, 8, '2027-11-10', 'https://images.unsplash.com/photo-1576073719676-aa955fc6b567?w=300&auto=format&fit=crop&q=80', 'active'),
(7, 'Salbutamol Inhaler 100mcg', 4, 3, 'Metered dose inhaler bronchodilator for fast symptomatic relief of asthma bronchospasms and COPD shortness of breath.', 28.00, 12, '2026-08-15', 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=300&auto=format&fit=crop&q=80', 'active'),
(8, 'Ascorbic Acid + Zinc 1000mg', 5, 4, 'Daily high-potency Vitamin C antioxidant with elemental Zinc for immune system defense and cellular health.', 11.50, 95, '2027-05-25', 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=300&auto=format&fit=crop&q=80', 'active'),
(9, 'Azithromycin 500mg Tablets', 1, 1, 'Macrolide antibacterial drug with high tissue penetration for respiratory infections, pneumonia, and strep throat.', 18.50, 18, '2026-09-25', 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&auto=format&fit=crop&q=80', 'active'),
(10, 'Metformin HCl 500mg', 3, 2, 'Oral antihyperglycemic medication used to improve glycemic blood sugar control in adults with type 2 diabetes mellitus.', 7.00, 5, '2026-07-20', 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=300&auto=format&fit=crop&q=80', 'active'),
(11, 'Hydrocortisone 1% Cream', 7, 4, 'Topical anti-inflammatory corticosteroid cream for temporary relief of itching and rash associated with eczema.', 9.50, 45, '2027-10-30', 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&auto=format&fit=crop&q=80', 'active'),
(12, 'Ciprofloxacin 500mg Tablets', 1, 3, 'Potent fluoroquinolone antibiotic for urinary tract infections, severe bronchitis, and bone/joint infections.', 14.00, 60, '2027-04-12', 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=300&auto=format&fit=crop&q=80', 'active');

-- Seed associated stocks batches
INSERT INTO `stocks` (`id`, `medicine_id`, `batch_number`, `quantity`, `purchase_date`, `expiry_date`) VALUES
(1, 1, 'AMX-2026-01', 140, '2026-01-10', '2027-06-30'),
(2, 2, 'PCM-2026-04', 6,   '2026-02-15', '2027-12-31'),
(3, 3, 'ATV-2026-02', 85,  '2026-03-01', '2027-09-15'),
(4, 4, 'CTZ-2026-08', 210, '2026-04-12', '2027-08-20'),
(5, 5, 'OMP-2025-09', 4,   '2025-09-18', '2026-09-20'),
(6, 6, 'IBU-2026-03', 8,   '2026-03-20', '2027-11-10'),
(7, 7, 'SLB-2024-11', 12,  '2024-11-15', '2026-08-15'),
(8, 8, 'VIT-2026-05', 95,  '2026-05-10', '2027-05-25'),
(9, 9, 'AZM-2025-10', 18,  '2025-10-01', '2026-09-25'),
(10, 10, 'MTF-2024-07', 5, '2024-07-20', '2026-07-20'),
(11, 11, 'HYD-2026-06', 45, '2026-06-15', '2027-10-30'),
(12, 12, 'CIP-2026-02', 60, '2026-02-28', '2027-04-12');

-- Seed associated stock history
INSERT INTO `stock_history` (`id`, `medicine_id`, `transaction_type`, `quantity`, `balance_after`, `reference_no`, `notes`, `user_id`, `created_at`) VALUES
(1, 1, 'PURCHASE', 100, 140, 'PO-2026-0891', 'Stock received from MedSupply Global', 1, DATE_SUB(NOW(), INTERVAL 15 MINUTE)),
(2, 2, 'SALE', -4, 6, 'INV-2026-1044', 'Prescription dispensed to patient', 2, DATE_SUB(NOW(), INTERVAL 45 MINUTE)),
(3, 5, 'SALE', -2, 4, 'INV-2026-1043', 'Over-the-counter sales transaction', 2, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(4, 6, 'SALE', -6, 8, 'INV-2026-1042', 'Prescription dispensed to patient', 1, DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(5, 7, 'EXPIRED', -3, 12, 'AUD-2026-012', 'Removed damaged expired inhaler units', 1, DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(6, 9, 'PURCHASE', 20, 18, 'PO-2026-0889', 'Emergency restock batch from Cipla', 2, DATE_SUB(NOW(), INTERVAL 8 HOUR)),
(7, 3, 'SALE', -5, 85, 'INV-2026-1040', 'Monthly refill dispensed', 2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(8, 4, 'PURCHASE', 150, 210, 'PO-2026-0885', 'Seasonal allergy restock consignment', 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(9, 8, 'ADJUSTMENT', +10, 95, 'ADJ-2026-004', 'Physical inventory stock audit count adjustment', 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(10, 10, 'EXPIRED', -15, 5, 'AUD-2026-011', 'Quarantined expired diabetes tablets', 2, DATE_SUB(NOW(), INTERVAL 3 DAY));
