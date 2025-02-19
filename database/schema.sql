-- Create database if not exists
CREATE DATABASE IF NOT EXISTS pagasa_cyclone;
USE pagasa_cyclone;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone_number VARCHAR(20),
    location_lat DECIMAL(10, 8),
    location_lng DECIMAL(11, 8),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Cyclone data table
CREATE TABLE IF NOT EXISTS cyclones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    status VARCHAR(50),
    wind_speed DECIMAL(10, 2),
    pressure DECIMAL(10, 2),
    current_lat DECIMAL(10, 8),
    current_lng DECIMAL(11, 8),
    formation_date DATETIME,
    dissipation_date DATETIME,
    last_update TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Cyclone tracking points
CREATE TABLE IF NOT EXISTS cyclone_tracks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cyclone_id INT,
    lat DECIMAL(10, 8),
    lng DECIMAL(11, 8),
    wind_speed DECIMAL(10, 2),
    pressure DECIMAL(10, 2),
    timestamp DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cyclone_id) REFERENCES cyclones(id) ON DELETE CASCADE
);

-- Hazard zones
CREATE TABLE IF NOT EXISTS hazard_zones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type ENUM('flood', 'landslide', 'storm_surge', 'wind_damage'),
    risk_level ENUM('low', 'medium', 'high', 'extreme'),
    boundary_points JSON,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Alerts and warnings
CREATE TABLE IF NOT EXISTS alerts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    type ENUM('warning', 'watch', 'advisory', 'update'),
    severity ENUM('low', 'medium', 'high', 'extreme'),
    affected_areas JSON,
    start_time DATETIME,
    end_time DATETIME,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Community reports
CREATE TABLE IF NOT EXISTS community_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    type ENUM('flooding', 'damage', 'casualty', 'other'),
    severity ENUM('low', 'medium', 'high', 'extreme'),
    lat DECIMAL(10, 8),
    lng DECIMAL(11, 8),
    images JSON,
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Emergency resources
CREATE TABLE IF NOT EXISTS emergency_resources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type ENUM('shelter', 'medical', 'food', 'rescue'),
    capacity INT,
    status ENUM('available', 'full', 'closed'),
    lat DECIMAL(10, 8),
    lng DECIMAL(11, 8),
    contact_info JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- User alert subscriptions
CREATE TABLE IF NOT EXISTS alert_subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    alert_type ENUM('email', 'sms', 'push'),
    area_of_interest JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create indexes
CREATE INDEX idx_cyclones_name ON cyclones(name);
CREATE INDEX idx_cyclone_tracks_cyclone_id ON cyclone_tracks(cyclone_id);
CREATE INDEX idx_hazard_zones_type ON hazard_zones(type);
CREATE INDEX idx_alerts_type ON alerts(type);
CREATE INDEX idx_community_reports_user_id ON community_reports(user_id);
CREATE INDEX idx_emergency_resources_type ON emergency_resources(type); 