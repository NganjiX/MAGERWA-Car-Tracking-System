# 🚛 MAGERWA Vehicle Tracking System

A premium vehicle management system for **MAGERWA (Magasins Généraux du Rwanda)**, Rwanda's public bonded warehouse. The system enables administrators to manage clients, vehicles, and vehicle-client associations through a modern and responsive dashboard.

---

## ✨ Features

### 🔐 Authentication

* Admin Registration (Signup)
* Admin Login
* Secure Session Management

### 👥 Client Management

* Create, Read, Update, Delete (CRUD) Clients
* Search and Filter Clients
* Pagination Support

### 🚗 Vehicle Management

* Create, Read, Update, Delete (CRUD) Vehicles
* Search and Filter Vehicles
* Pagination Support

### 🔗 Vehicle–Client Linking

* Link Vehicles to Clients
* Unique Plate Number Management
* Update Vehicle Associations
* Unlink Vehicles

### 📊 Dashboard

* Real-Time Statistics
* Activity Feed
* Quick Overview Cards

### 🎨 Modern User Interface

* Responsive Design
* Bootstrap 5 Integration
* Glassmorphism Effects
* Smooth Animations
* User-Friendly Navigation

---

## 🛠️ Tech Stack

| Technology   | Version |
| ------------ | ------- |
| PHP          | 7.4+    |
| MySQL        | 8.0+    |
| Bootstrap    | 5.1     |
| jQuery       | 3.6     |
| Font Awesome | 6       |

---

## 📂 Project Structure

```text
vehicle-tracking-system/
│
├── api/                # REST API Endpoints
├── assets/             # CSS, JavaScript, Images
├── config/             # Database Configuration
├── includes/           # Helper Functions
├── pages/              # Frontend Pages
├── sql/                # Database Schema
└── index.php           # Application Entry Point
```

---

## 📥 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/YOUR_USERNAME/vehicle-tracking-system.git
```

### 2. Move Project to Your Web Server Root

#### XAMPP

```text
C:\xampp\htdocs\
```

#### WAMP

```text
C:\wamp\www\
```

#### MAMP

```text
/Applications/MAMP/htdocs/
```

---

### 3. Configure the Database

Open:

```php
config/database.php
```

Update the database credentials:

```php
private $host = 'localhost';
private $db_name = 'magerwa_vehicle_tracking';
private $username = 'root';
private $password = '';
```

---

### 4. Import the Database

Import the SQL schema located at:

```text
sql/database.sql
```

You can import it using:

* phpMyAdmin
* MySQL Workbench
* MySQL CLI

---

### 5. Launch the Application

Open your browser and navigate to:

```text
http://localhost/vehicle-tracking-system/
```

---

## 🗄️ Database Structure

### admins

Stores administrator accounts.

### clients

Stores client information.

### vehicles

Stores vehicle records.

### vehicle_links

Stores vehicle-client associations and plate numbers.

---

## 📚 API Documentation

### Authentication

| Method | Endpoint                      | Description    |
| ------ | ----------------------------- | -------------- |
| POST   | `/api/auth.php?action=signup` | Register Admin |
| POST   | `/api/auth.php?action=login`  | Login Admin    |

---

### Clients

| Method | Endpoint                | Description   |
| ------ | ----------------------- | ------------- |
| GET    | `/api/clients.php`      | Get Clients   |
| POST   | `/api/clients.php`      | Create Client |
| PUT    | `/api/clients.php?id=1` | Update Client |
| DELETE | `/api/clients.php?id=1` | Delete Client |

---

### Vehicles

| Method | Endpoint                 | Description    |
| ------ | ------------------------ | -------------- |
| GET    | `/api/vehicles.php`      | Get Vehicles   |
| POST   | `/api/vehicles.php`      | Create Vehicle |
| PUT    | `/api/vehicles.php?id=1` | Update Vehicle |
| DELETE | `/api/vehicles.php?id=1` | Delete Vehicle |

---

### Vehicle Links

| Method | Endpoint                        | Description            |
| ------ | ------------------------------- | ---------------------- |
| GET    | `/api/links.php`                | Get Linked Vehicles    |
| GET    | `/api/links.php?available=true` | Get Available Vehicles |
| POST   | `/api/links.php`                | Link Vehicle           |
| PUT    | `/api/links.php?id=1`           | Update Plate Number    |
| DELETE | `/api/links.php?id=1`           | Unlink Vehicle         |

---

## 🚀 Future Enhancements

* Vehicle History Tracking
* Export Reports (PDF/Excel)
* Role-Based Access Control
* Email Notifications
* Audit Logs
* API Authentication with JWT

---

## 🤝 Contributing

Contributions are welcome!

1. Fork the repository
2. Create a feature branch

```bash
git checkout -b feature-name
```

3. Commit your changes

```bash
git commit -m "Add new feature"
```

4. Push to your branch

```bash
git push origin feature-name
```

5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Author

**NGANJI Heaven's**

Made with ❤️ for MAGERWA Vehicle Management.
