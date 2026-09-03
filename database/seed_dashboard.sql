-- Seed data for Pharmacy Management System Dashboard & Inventory
USE `pharmacy_db`;

-- 1. Suppliers
INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`, `email`, `address`, `status`) VALUES
(1, 'MedSupply Global Corp', 'Robert Fox', '+1 555-0192', 'contact@medsupply.com', '742 Evergreen Terrace, Springfield', 'active'),
(2, 'Novartis Pharma Distributors', 'Elena Vance', '+1 555-0144', 'orders@novartis-dist.com', '104 Healthcare Blvd, Boston, MA', 'active'),
(3, 'Cipla Healthcare Ltd', 'Rajesh Kumar', '+1 555-0188', 'support@cipla-dist.com', '45 Industrial Zone, Mumbai', 'active'),
(4, 'Sun Pharma Logistics', 'Amit Patel', '+1 555-0177', 'sales@sunpharma-supply.com', '12 Trade Hub, New Jersey', 'active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 2. Medicines Catalog
INSERT INTO `medicines` (`id`, `category_id`, `supplier_id`, `name`, `generic_name`, `sku`, `barcode`, `dosage_form`, `strength`, `buy_price`, `sell_price`, `unit`, `min_stock_alert`, `expiry_date`, `description`, `status`) VALUES
(1, 1, 1, 'Amoxicillin Trihydrate', 'Amoxicillin', 'MED-AMX-500', '890123456701', 'Capsule', '500mg', 6.50, 12.00, 'Box (100s)', 20, '2027-06-30', 'Broad-spectrum penicillin antibiotic', 'active'),
(2, 2, 2, 'Paracetamol Extra', 'Acetaminophen', 'MED-PCM-650', '890123456702', 'Tablet', '650mg', 2.00, 4.50, 'Strip (10s)', 30, '2027-12-31', 'Antipyretic and analgesic for pain and fever', 'active'),
(3, 3, 2, 'Atorvastatin Calcium', 'Atorvastatin', 'MED-ATV-20', '890123456703', 'Tablet', '20mg', 12.00, 24.50, 'Strip (15s)', 15, '2027-09-15', 'Statin medication for lowering cholesterol', 'active'),
(4, 4, 3, 'Cetirizine Hydrochloride', 'Cetirizine', 'MED-CTZ-10', '890123456704', 'Tablet', '10mg', 3.00, 6.50, 'Strip (10s)', 25, '2027-08-20', 'Second-generation antihistamine for allergies', 'active'),
(5, 6, 1, 'Omeprazole DR', 'Omeprazole', 'MED-OMP-20', '890123456705', 'Capsule', '20mg', 8.00, 16.00, 'Strip (14s)', 15, '2026-09-18', 'Proton pump inhibitor for acid reflux and GERD', 'active'), -- Expiring soon
(6, 2, 4, 'Ibuprofen Rapid Release', 'Ibuprofen', 'MED-IBU-400', '890123456706', 'Tablet', '400mg', 4.00, 8.00, 'Strip (10s)', 20, '2027-11-10', 'Nonsteroidal anti-inflammatory drug (NSAID)', 'active'),
(7, 4, 3, 'Salbutamol HFA Inhaler', 'Albuterol', 'MED-SLB-100', '890123456707', 'Inhaler', '100mcg', 15.00, 28.00, 'Inhaler (200 doses)', 10, '2026-08-15', 'Short-acting bronchodilator for asthma relief', 'active'), -- Expired
(8, 5, 4, 'Ascorbic Acid + Zinc', 'Vitamin C + Zinc', 'MED-VIT-C1000', '890123456708', 'Effervescent', '1000mg', 5.00, 11.50, 'Tube (20s)', 15, '2027-05-25', 'Immune support dietary multivitamin', 'active'),
(9, 1, 1, 'Azithromycin Monohydrate', 'Azithromycin', 'MED-AZM-500', '890123456709', 'Tablet', '500mg', 9.00, 18.50, 'Strip (3s)', 15, '2026-09-25', 'Macrolide antibiotic for respiratory infections', 'active'), -- Expiring soon
(10, 3, 2, 'Metformin Hydrochloride', 'Metformin', 'MED-MTF-500', '890123456710', 'Tablet', '500mg', 3.50, 7.00, 'Strip (10s)', 25, '2026-07-20', 'First-line medication for type 2 diabetes', 'active'), -- Expired
(11, 7, 4, 'Hydrocortisone Top Cream', 'Hydrocortisone', 'MED-HYD-1', '890123456711', 'Ointment', '1% (30g)', 4.50, 9.50, 'Tube (30g)', 10, '2027-10-30', 'Mild corticosteroid for dermatitis and skin rashes', 'active'),
(12, 1, 3, 'Ciprofloxacin USP', 'Ciprofloxacin', 'MED-CIP-500', '890123456712', 'Tablet', '500mg', 7.00, 14.00, 'Strip (10s)', 12, '2027-04-12', 'Fluoroquinolone antibiotic for bacterial infections', 'active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 3. Stock Batches (Healthy, Low Stock, Expiring Soon, Expired)
-- Current reference date is ~September 2026
DELETE FROM `stocks`;
INSERT INTO `stocks` (`id`, `medicine_id`, `batch_number`, `quantity`, `purchase_date`, `expiry_date`) VALUES
(1, 1, 'AMX-2026-01', 140, '2026-01-10', '2027-06-30'), -- Healthy
(2, 2, 'PCM-2026-04', 6,   '2026-02-15', '2027-12-31'), -- Low stock (6 < 30)
(3, 3, 'ATV-2026-02', 85,  '2026-03-01', '2027-09-15'), -- Healthy
(4, 4, 'CTZ-2026-08', 210, '2026-04-12', '2027-08-20'), -- Healthy
(5, 5, 'OMP-2025-09', 4,   '2025-09-18', '2026-09-18'), -- Low stock (4 < 15) & Expiring Soon (~17 days)
(6, 6, 'IBU-2026-03', 8,   '2026-03-20', '2027-11-10'), -- Low stock (8 < 20)
(7, 7, 'SLB-2024-11', 12,  '2024-11-15', '2026-08-15'), -- Expired (past date)
(8, 8, 'VIT-2026-05', 95,  '2026-05-10', '2027-05-25'), -- Healthy
(9, 9, 'AZM-2025-10', 18,  '2025-10-01', '2026-09-25'), -- Expiring soon (~24 days)
(10, 10, 'MTF-2024-07', 5, '2024-07-20', '2026-07-20'), -- Expired (past date) & Low stock (5 < 25)
(11, 11, 'HYD-2026-06', 45, '2026-06-15', '2027-10-30'), -- Healthy
(12, 12, 'CIP-2026-02', 60, '2026-02-28', '2027-04-12'); -- Healthy

-- 4. Stock Activity History
DELETE FROM `stock_history`;
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
