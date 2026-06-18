📦 MAGERWA Vehicle Tracking System
A premium vehicle management system for MAGERWA, Rwanda's public bonded warehouse.

✨ Features
Admin Authentication - Signup/Login with secure sessions

Client Management - CRUD operations with search

Vehicle Management - CRUD operations with search

Vehicle-Client Linking - Link vehicles with unique plate numbers

Premium Dashboard - Real-time stats and activity feed

Modern UI - Glassmorphism, animations, responsive design

🛠️ Tech Stack
PHP 7.4+

MySQL 8.0

Bootstrap 5.1

jQuery 3.6

Font Awesome 6

📥 Installation
1. Clone or Download
bash
git clone https://github.com/YOUR_USERNAME/vehicle-tracking-system.git
2. Move to Web Server Root
XAMPP: C:\xampp\htdocs\

WAMP: C:\wamp\www\

MAMP: /Applications/MAMP/htdocs/

3. Update Database Config
Edit config/database.php:

php
private $host = 'localhost';
private $db_name = 'magerwa_vehicle_tracking';
private $username = 'root';
private $password = '';
4. Import Database
Run sql/database.sql in phpMyAdmin or MySQL CLI.

5. Access Application
text
http://localhost/vehicle-tracking-system/
🗄️ Database Structure
admins - Admin accounts

clients - Client information

vehicles - Vehicle details

vehicle_links - Vehicle-Client associations with plate numbers

📁 Project Structure
text
vehicle-tracking-system/
├── api/           # API endpoints
├── assets/        # CSS, JS
├── config/        # Database config
├── includes/      # Helper functions
├── pages/         # Frontend pages
├── sql/           # Database schema
└── index.php      # Entry point
📚 API Endpoints
Authentication
POST /api/auth.php?action=signup - Register admin

POST /api/auth.php?action=login - Admin login

Clients
GET /api/clients.php - Get clients (paginated)

POST /api/clients.php - Create client

PUT /api/clients.php?id=1 - Update client

DELETE /api/clients.php?id=1 - Delete client

Vehicles
GET /api/vehicles.php - Get vehicles (paginated)

POST /api/vehicles.php - Create vehicle

PUT /api/vehicles.php?id=1 - Update vehicle

DELETE /api/vehicles.php?id=1 - Delete vehicle

Links
GET /api/links.php - Get linked vehicles

GET /api/links.php?available=true - Get available vehicles

POST /api/links.php - Link vehicle

PUT /api/links.php?id=1 - Update plate

DELETE /api/links.php?id=1 - Unlink vehicle

🤝 Contributing
Fork the repository

Create a feature branch

Commit your changes

Push and create a Pull Request

📄 License
MIT License

Made with ❤️ by NGANJI Heaven's
