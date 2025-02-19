-- Insert test hazard zones
INSERT INTO hazard_zones (
    name,
    type,
    risk_level,
    boundary_points,
    description
) VALUES 
(
    'Manila Bay Storm Surge Zone',
    'storm_surge',
    'high',
    '{
        "type": "Polygon",
        "coordinates": [[
            [120.9673, 14.5831],
            [120.9912, 14.5934],
            [121.0183, 14.5789],
            [121.0084, 14.5537],
            [120.9673, 14.5831]
        ]]
    }',
    'High-risk storm surge area along Manila Bay coastline. Historical surge heights of 2-4 meters recorded.'
),
(
    'Marikina Valley Flood Zone',
    'flood',
    'extreme',
    '{
        "type": "Polygon",
        "coordinates": [[
            [121.0953, 14.6527],
            [121.1033, 14.6684],
            [121.1156, 14.6621],
            [121.1078, 14.6482],
            [121.0953, 14.6527]
        ]]
    }',
    'Extreme flood risk area in Marikina Valley. Prone to rapid flooding during heavy rainfall.'
),
(
    'Antipolo Landslide Risk Area',
    'landslide',
    'medium',
    '{
        "type": "Polygon",
        "coordinates": [[
            [121.1752, 14.5862],
            [121.1852, 14.5962],
            [121.1952, 14.5862],
            [121.1852, 14.5762],
            [121.1752, 14.5862]
        ]]
    }',
    'Medium-risk landslide area in hilly regions of Antipolo. Soil instability during heavy rains.'
),
(
    'Quezon City Wind Corridor',
    'wind_damage',
    'high',
    '{
        "type": "Polygon",
        "coordinates": [[
            [121.0431, 14.6476],
            [121.0531, 14.6576],
            [121.0631, 14.6476],
            [121.0531, 14.6376],
            [121.0431, 14.6476]
        ]]
    }',
    'High-risk area for wind damage during tropical cyclones. Urban corridor effect amplifies wind speeds.'
),
(
    'Pasig River Flood Plain',
    'flood',
    'high',
    '{
        "type": "Polygon",
        "coordinates": [[
            [121.0544, 14.5747],
            [121.0644, 14.5847],
            [121.0744, 14.5747],
            [121.0644, 14.5647],
            [121.0544, 14.5747]
        ]]
    }',
    'High-risk flood area along Pasig River. Regular flooding during monsoon season and tropical cyclones.'
); 