CREATE DATABASE IF NOT EXISTS scheduler_dev;
CREATE USER IF NOT EXISTS 'scheduler_dev'@'%' IDENTIFIED BY 'scheduler_dev';
GRANT ALL PRIVILEGES ON scheduler_dev.* TO 'scheduler_dev'@'%';

CREATE DATABASE IF NOT EXISTS scheduler_test;
CREATE USER IF NOT EXISTS 'scheduler_test'@'%' IDENTIFIED BY 'scheduler_test';
GRANT ALL PRIVILEGES ON scheduler_test.* TO 'scheduler_test'@'%';
