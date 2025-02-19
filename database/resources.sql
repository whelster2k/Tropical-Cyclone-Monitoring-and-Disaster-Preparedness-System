-- Create resources table
CREATE TABLE IF NOT EXISTS `resources` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `type` enum('shelter', 'medical', 'relief') NOT NULL,
    `description` text,
    `address` text NOT NULL,
    `latitude` decimal(10,8) NOT NULL,
    `longitude` decimal(11,8) NOT NULL,
    `contact_number` varchar(50),
    `capacity` int(11),
    `status` enum('active', 'inactive', 'full') NOT NULL DEFAULT 'active',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_type` (`type`),
    KEY `idx_status` (`status`),
    KEY `idx_location` (`latitude`, `longitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create resource_availability table
CREATE TABLE IF NOT EXISTS `resource_availability` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `resource_id` int(11) NOT NULL,
    `quantity` int(11) NOT NULL DEFAULT 0,
    `status` enum('available', 'limited', 'unavailable') NOT NULL DEFAULT 'available',
    `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_resource` (`resource_id`),
    CONSTRAINT `fk_resource_availability` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO `resources` (`name`, `type`, `description`, `address`, `latitude`, `longitude`, `contact_number`, `capacity`, `status`) VALUES
('Davao City Evacuation Center', 'shelter', 'Main evacuation center with complete facilities', 'San Pedro St, Davao City', 7.190708, 125.455341, '+63 82 224 1234', 500, 'active'),
('Southern Philippines Medical Center', 'medical', '24/7 emergency medical services', 'JP Laurel Ave, Davao City', 7.098174, 125.607365, '+63 82 227 2731', 1000, 'active'),
('Cagayan de Oro Relief Center', 'relief', 'Main distribution point for relief goods', 'Carmen, Cagayan de Oro City', 8.479168, 124.647781, '+63 88 857 2345', 1000, 'active'),
('Butuan City Emergency Shelter', 'shelter', 'Secondary evacuation facility', 'JC Aquino Ave, Butuan City', 8.949543, 125.543886, '+63 85 342 5678', 300, 'active'),
('Zamboanga City General Hospital', 'medical', 'Emergency medical facility', 'Veterans Ave, Zamboanga City', 6.913594, 122.079870, '+63 62 991 2934', 500, 'active'),
('Surigao Relief Operations Center', 'relief', 'Relief goods distribution hub', 'Surigao City', 9.784724, 125.498284, '+63 86 826 1234', 800, 'active'); 