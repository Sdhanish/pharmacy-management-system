-- Prompt 7: Customers Migration & Seed Data
USE `pharmacy_db`;

-- Update users table structure
ALTER TABLE `users` 
  MODIFY COLUMN `role` ENUM('admin', 'pharmacist', 'cashier', 'customer') NOT NULL DEFAULT 'customer';

-- Add phone and address columns if they do not exist
SET @dbname = DATABASE();
SET @tablename = 'users';
SET @columnname = 'phone';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `users` ADD COLUMN `phone` VARCHAR(30) DEFAULT NULL AFTER `email`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SET @columnname = 'address';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `users` ADD COLUMN `address` TEXT DEFAULT NULL AFTER `phone`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Seed Sample Customer Records
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `password`, `role`, `status`) VALUES
(3, 'Johnathan Miller', 'john.miller@example.com', '+1 555-0182', '742 Evergreen Terrace, Springfield', '$2y$10$tZ2c6r7jN19mD9Z5Xm0zUe7uX3f5K1w4Z2c6r7jN19mD9Z5Xm0zUe', 'customer', 'active'),
(4, 'Emily Watson', 'emily.watson@example.com', '+1 555-0143', '104 Healthcare Blvd, Apt 3B, Boston, MA', '$2y$10$tZ2c6r7jN19mD9Z5Xm0zUe7uX3f5K1w4Z2c6r7jN19mD9Z5Xm0zUe', 'customer', 'active'),
(5, 'Michael Chang', 'michael.chang@example.com', '+1 555-0199', '12 Pine Valley Road, Austin, TX', '$2y$10$tZ2c6r7jN19mD9Z5Xm0zUe7uX3f5K1w4Z2c6r7jN19mD9Z5Xm0zUe', 'customer', 'active'),
(6, 'Sophia Rodriguez', 'sophia.r@example.com', '+1 555-0167', '88 Sunset Strip, Suite 400, Los Angeles, CA', '$2y$10$tZ2c6r7jN19mD9Z5Xm0zUe7uX3f5K1w4Z2c6r7jN19mD9Z5Xm0zUe', 'customer', 'active'),
(7, 'David Kim', 'david.kim@example.com', '+1 555-0128', '230 Elmwood Avenue, Chicago, IL', '$2y$10$tZ2c6r7jN19mD9Z5Xm0zUe7uX3f5K1w4Z2c6r7jN19mD9Z5Xm0zUe', 'customer', 'inactive')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `phone`=VALUES(`phone`), `address`=VALUES(`address`), `role`=VALUES(`role`), `status`=VALUES(`status`);
