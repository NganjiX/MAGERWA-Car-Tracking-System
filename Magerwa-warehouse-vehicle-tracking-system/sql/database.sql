-- sql/database.sql

-- Create database
CREATE DATABASE IF NOT EXISTS magerwa_vehicle_tracking;
USE magerwa_vehicle_tracking;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    names VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Clients table
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    names VARCHAR(100) NOT NULL,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Vehicles table
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chassis_number VARCHAR(50) UNIQUE NOT NULL,
    manufacture_company VARCHAR(100) NOT NULL,
    manufacture_year YEAR NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    model_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Vehicle-Client Linkage table
CREATE TABLE IF NOT EXISTS vehicle_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    client_id INT NOT NULL,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    linked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vehicle (vehicle_id),
    UNIQUE KEY unique_plate (plate_number)
);

-- Indexes for better performance
CREATE INDEX idx_plate_number ON vehicle_links(plate_number);
CREATE INDEX idx_chassis ON vehicles(chassis_number);
CREATE INDEX idx_client_nid ON clients(national_id);
CREATE INDEX idx_admin_email ON admins(email);
CREATE INDEX idx_admin_nid ON admins(national_id);

-- Insert a default admin (optional - remove if you want to create one via signup)
-- INSERT INTO admins (names, email, phone, national_id, password) 
-- VALUES ('Admin User', 'admin@magerwa.com', '0788000000', '1234567890123456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Note: The password above is 'password' hashed