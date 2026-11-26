-- Database used by the PHPUnit suite (RefreshDatabase wipes it on every run).
CREATE DATABASE IF NOT EXISTS panobooking_test;
GRANT ALL PRIVILEGES ON panobooking_test.* TO 'panobooking'@'%';
FLUSH PRIVILEGES;
