<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' . getenv('APP_NAME') : getenv('APP_NAME'); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    
    <!-- Custom Styles -->
    <style type="text/css">
        /* Base styles */
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #666;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #888;
        }
    </style>

    <script>
        // Enhanced dark mode configuration for Tailwind with improved readability
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            bg: '#111827',      // Gray 900
                            card: '#1f2937',    // Gray 800
                            secondary: '#374151', // Gray 700
                            text: '#f9fafb',     // Gray 50 - Main text
                            'text-secondary': '#e5e7eb', // Gray 200 - Secondary text
                            muted: '#d1d5db',    // Gray 300 - Muted text
                            border: '#4b5563',   // Gray 600
                            hover: '#2563eb',    // Blue 600
                            accent: '#3b82f6',   // Blue 500
                            input: '#374151',    // Gray 700 - Form inputs
                            'input-focus': '#4b5563', // Gray 600 - Form input focus
                            success: '#059669',  // Green 600
                            error: '#dc2626',    // Red 600
                            warning: '#d97706',  // Yellow 600
                            info: '#2563eb',     // Blue 600
                            'card-light': '#2d3748',  // Lighter card background
                            'card-dark': '#1a202c',   // Darker card background
                            'button-hover': '#1d4ed8', // Darker blue for button hover
                            'link-hover': '#60a5fa'    // Lighter blue for link hover
                        }
                    },
                    boxShadow: {
                        'dark-sm': '0 2px 4px 0 rgba(0, 0, 0, 0.4)',
                        'dark-md': '0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.4)',
                        'dark-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.4)',
                        'dark-xl': '0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.4)'
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Windy API -->
    <script src="https://api.windy.com/assets/map-forecast/libBoot.js"></script>
    
    <!-- Leaflet Velocity Layer -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-velocity@1.7.0/dist/leaflet-velocity.css" />
    <script src="https://unpkg.com/leaflet-velocity@1.7.0/dist/leaflet-velocity.js"></script>
    
    <!-- Rain Canvas Layer -->
    <script src="https://unpkg.com/leaflet-raincanvas@1.0.0/dist/leaflet-raincanvas.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col dark:bg-dark-bg dark:text-dark-text transition-all duration-200">
    <!-- Navigation -->
    <nav class="bg-white shadow-md dark:bg-dark-card dark:shadow-dark-md border-b border-gray-200 dark:border-dark-border transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/" class="text-xl font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                            PAGASA
                        </a>
                    </div>
                    <!-- Navigation Links -->
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="/pagasa/public/cyclones" 
                           class="inline-flex items-center px-1 pt-1 text-gray-700 dark:text-dark-text hover:text-blue-600 dark:hover:text-blue-300 border-b-2 border-transparent hover:border-blue-600 dark:hover:border-blue-400 transition-all">
                            Cyclones
                        </a>
                        <a href="/pagasa/public/hazards" 
                           class="inline-flex items-center px-1 pt-1 text-gray-700 dark:text-dark-text hover:text-blue-600 dark:hover:text-blue-300 border-b-2 border-transparent hover:border-blue-600 dark:hover:border-blue-400 transition-all">
                            Hazards
                        </a>
                        <a href="/pagasa/public/resources" 
                           class="inline-flex items-center px-1 pt-1 text-gray-700 dark:text-dark-text hover:text-blue-600 dark:hover:text-blue-300 border-b-2 border-transparent hover:border-blue-600 dark:hover:border-blue-400 transition-all">
                            Resources
                        </a>
                    </div>
                </div>
                <!-- User Menu & Theme Toggle -->
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-dark-secondary hover:bg-gray-200 dark:hover:bg-dark-input-focus text-gray-500 dark:text-dark-muted transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-dark-accent">
                        <!-- Sun icon for dark mode -->
                        <svg class="w-5 h-5 hidden dark:block text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                        </svg>
                        <!-- Moon icon for light mode -->
                        <svg class="w-5 h-5 block dark:hidden text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                        </svg>
                    </button>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/profile" 
                           class="text-gray-700 dark:text-dark-text hover:text-blue-600 dark:hover:text-blue-300 px-3 py-2 rounded-md transition-colors">
                            Profile
                        </a>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/logout" 
                           class="text-gray-700 dark:text-dark-text hover:text-blue-600 dark:hover:text-blue-300 px-3 py-2 rounded-md transition-colors">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/login" 
                           class="text-gray-700 dark:text-dark-text hover:text-blue-600 dark:hover:text-blue-300 px-3 py-2 rounded-md transition-colors">
                            Login
                        </a>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/register" 
                           class="bg-blue-600 dark:bg-dark-accent text-white px-4 py-2 rounded-md hover:bg-blue-700 dark:hover:bg-dark-button-hover transition-colors">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 dark:bg-dark-success/20 border border-green-400 dark:border-dark-success text-green-700 dark:text-green-200 px-4 py-3 rounded-lg shadow-sm dark:shadow-dark-sm transition-all duration-200" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 dark:bg-dark-error/20 border border-red-400 dark:border-dark-error text-red-700 dark:text-red-200 px-4 py-3 rounded-lg shadow-sm dark:shadow-dark-sm transition-all duration-200" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 py-6 w-full">
        <?php if (isset($content)) echo $content; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-dark-card shadow-md dark:shadow-dark-md border-t border-gray-200 dark:border-dark-border mt-auto transition-all duration-200">
        <div class="max-w-7xl mx-auto py-4 px-4">
            <div class="text-center text-gray-600 dark:text-dark-text-secondary">
                &copy; <?php echo date('Y'); ?> PAGASA Cyclone Monitoring. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Theme Toggle Script -->
    <script>
        // Check for saved theme preference, otherwise use system preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Enhanced theme toggle functionality with smooth transition
        document.getElementById('theme-toggle').addEventListener('click', function() {
            const html = document.documentElement;
            const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Add transition class
            html.classList.add('transition-colors', 'duration-300');
            
            // Toggle theme
            if (newTheme === 'dark') {
                html.classList.add('dark');
                localStorage.theme = 'dark';
            } else {
                html.classList.remove('dark');
                localStorage.theme = 'light';
            }
            
            // Remove transition class after animation
            setTimeout(() => {
                html.classList.remove('transition-colors', 'duration-300');
            }, 300);
        });
    </script>

    <!-- Scripts -->
    <script src="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/js/app.js"></script>
</body>
</html> 