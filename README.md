# Tropical Cyclone Monitoring and Disaster Preparedness System

A comprehensive web-based system for monitoring tropical cyclones, assessing hazards, and managing disaster preparedness.

## Features

- Real-time Tropical Cyclone Monitoring
- Hazard Assessment and Risk Mapping
- Early Warning System
- Community Reports & Feedback
- Interactive GIS Integration
- Admin Dashboard
- User Management System

## Tech Stack

- **Frontend:**
  - HTML5/CSS3
  - Tailwind CSS (Styling)
  - Framer Motion (Animations)
  - Leaflet.js (Maps)
  - Chart.js (Data Visualization)

- **Backend:**
  - PHP
  - MySQL Database
  - REST APIs

## Prerequisites

- PHP >= 8.0
- MySQL >= 5.7
- Node.js >= 14.0
- Composer
- XAMPP/WAMP/MAMP

## Installation

1. Clone the repository
```bash
git clone [repository-url]
```

2. Install PHP dependencies
```bash
composer install
```

3. Install Node.js dependencies
```bash
npm install
```

4. Configure database
- Create a new MySQL database
- Copy `.env.example` to `.env`
- Update database credentials in `.env`

5. Run database migrations
```bash
php artisan migrate
```

6. Start the development server
```bash
php artisan serve
```

7. Compile assets
```bash
npm run dev
```

## Project Structure

```
├── app/                 # PHP application files
│   ├── controllers/    # Controller classes
│   ├── models/        # Database models
│   └── views/         # View templates
├── public/             # Publicly accessible files
│   ├── css/          # Compiled CSS
│   ├── js/           # Compiled JavaScript
│   └── images/       # Image assets
├── src/                # Source files
│   ├── css/          # Tailwind CSS files
│   └── js/           # JavaScript files
├── database/           # Database migrations and seeds
└── config/            # Configuration files
```

## Contributing

Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the LICENSE.md file for details. 