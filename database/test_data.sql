-- Insert test cyclones
INSERT INTO cyclones (
    name,
    category,
    status,
    wind_speed,
    pressure,
    current_lat,
    current_lng,
    formation_date,
    dissipation_date,
    last_update
) VALUES 
(
    'Typhoon Amang',
    'Tropical Storm',
    'active',
    80.5,
    995.0,
    14.5,
    121.5,
    NOW(),
    NULL,
    NOW()
),
(
    'Super Typhoon Bebeng',
    'Super Typhoon',
    'active',
    195.0,
    925.0,
    15.2,
    123.8,
    NOW(),
    NULL,
    NOW()
); 