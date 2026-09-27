# 🏠 HOMELY PG 🌟

_A modern, full-stack PG (Paying Guest) Accommodation Finder & Management Platform built with PHP, MongoDB Atlas, and Docker._

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MongoDB](https://img.shields.io/badge/MongoDB-Atlas_Cloud-47A248?style=for-the-badge&logo=mongodb&logoColor=white)](https://www.mongodb.com/cloud/atlas)
[![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![Render](https://img.shields.io/badge/Deploy-Render.com-46E3B7?style=for-the-badge&logo=render&logoColor=white)](https://render.com/)

---

## 🌐 Project Overview

**HOMELY PG** is a feature-rich web platform designed to simplify finding, booking, and managing Paying Guest accommodations for students and young professionals. 

It provides an intuitive experience for tenants to browse verified properties, filter by amenities and location, chat live with support, and upload identity verification documents. It also equips administrators with a full-fledged Management Portal to handle property listings, verify tenant identities, and respond to support messages in real-time.

---

## ✨ Features

### 🏢 Tenant & User Portal
- 🌆 **City & Amenity Filtering**: Search PGs by city (Mumbai, Delhi, Bengaluru, Hyderabad, etc.), price range, gender preference, and key amenities (Wi-Fi, AC, Food, Laundry, Gym).
- 🔒 **Secure Authentication**: Secure user registration, login, session persistence, and profile photo customization.
- 🪪 **Identity Verification System**: Securely upload Government ID cards (PDF/JPG/PNG) to obtain a verified tenant badge.
- 💬 **Interactive Live Chatbot & Widget**: Floating pop-up chat widget on every page for instant support and direct communication with admins.
- ❤️ **Interested Bookmarks**: Save favorite PG listings to your personal dashboard.

### 🛡️ Admin Management Portal (`/admin`)
- 📊 **Insightful Dashboard**: Monitor key metrics including total properties, registered users, pending verification documents, and chat messages.
- 🏠 **Property Management (CRUD)**: Create, edit, update amenities, upload property images, and soft/hard delete property listings.
- 👥 **User Management & Verification**: View all registered users, inspect uploaded ID cards, and toggle verified tenant status with one click.
- 💬 **Admin Chat Desk**: Unified chat inbox to review tenant queries, send real-time replies, and clear history.

### ⚡ Infrastructure & Cloud Upgrades
- 🍃 **MongoDB Atlas Integration**: Migrated backend to scalable cloud-native NoSQL database schemas using official `mongodb/mongodb` driver.
- 🐳 **1-Click Containerized Deployment**: Production-ready `Dockerfile` optimized for Render.com, Docker Desktop, or cloud platforms.
- 🎨 **Obsidian Glassmorphism Design**: Sleek dark footer, responsive layouts, smooth micro-animations, and unified modern UI theme.

---

## 🛠️ Technology Stack

| Component | Technology | Description |
|---|---|---|
| **Backend Core** | PHP 8.2 | Application routing, API endpoints, session security, and image uploads |
| **Database** | MongoDB Atlas | Cloud NoSQL DB for Users, Properties, Chats, and Verification documents |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+) | Custom glassmorphic styling, dynamic modals, and AJAX chat polling |
| **Containerization** | Docker & Apache (`mod_rewrite`) | Production web server packaging & environment isolation |
| **Dependencies** | Composer | Dependency management (`mongodb/mongodb` driver package) |

---

## 📁 Directory Structure

```text
Homely-PG-Project/
├── admin/                     # Admin Portal (Dashboard, CRUD, Verification & Chat)
│   ├── api/                   # Admin API endpoints (property save/delete, user toggle, reply)
│   ├── css/                   # Dedicated Admin styles
│   ├── includes/              # Admin header, footer, authentication guards
│   ├── dashboard.php          # Admin Overview Panel
│   ├── properties.php         # Manage & edit PG listings
│   ├── users.php              # User & ID verification desk
│   └── chat.php               # Admin Support Messaging Desk
├── api/                       # User-facing AJAX APIs (login, signup, interested toggle)
├── css/                       # Modular CSS stylesheets (home, dashboard, common, about)
├── includes/                  # Reusable components (header, footer, chat_widget, mongodb_connect)
├── scripts/                   # Migration & seeding utilities
│   ├── seed_mongodb_atlas.php # Database initializer script
│   └── migrate_mysql_to_mongodb.php
├── uploads/                   # Media & file storage
│   ├── id_cards/              # Uploaded verification documents
│   └── profile/               # Profile photos
├── Dockerfile                 # Containerized deployment blueprint
├── composer.json              # PHP dependencies
├── index.php                  # Main Landing Page
├── property_list.php          # Property Search & Filter Page
├── property_detail.php        # Property Details & Amenity View
└── README.md                  # Project Documentation
```

---

## 🚀 Installation & Setup

### Option 1: Quickstart with Docker 🐳 (Recommended)

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/piyush2602/Homely-PG-Project.git
   cd Homely-PG-Project
   ```

2. **Configure MongoDB Atlas URI**:
   Ensure your `includes/mongodb_connect.php` contains your valid MongoDB Atlas connection string.

3. **Build & Run Docker Container**:
   ```bash
   docker build -t homely-pg .
   docker run -d -p 8080:80 --name homely-pg-app homely-pg
   ```

4. **Access the App**:
   Open `http://localhost:8080` in your browser.

---

### Option 2: Local PHP & Apache Setup 💻

1. **Prerequisites**:
   - PHP 8.2+ installed with `mongodb` PHP extension enabled (`extension=mongodb` in `php.ini`).
   - Composer installed.
   - Apache / XAMPP Server.

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Seed Database**:
   Populate initial sample properties and users into your MongoDB Atlas cluster:
   ```bash
   php scripts/seed_mongodb_atlas.php
   ```

4. **Run Server**:
   Place the project folder inside your web server directory (e.g. `htdocs`) or run PHP built-in server:
   ```bash
   php -S localhost:8000
   ```

---

## 🌐 Live Demo & Deployment

- **Live Site**: [Homely PG Live](https://homely-pg-project.onrender.com/)
- **1-Click Render Deployment**: Simply connect this repo to [Render.com](https://render.com) and select **Web Service (Docker)** environment.

---

## 🤝 Contributing & Feedback

Contributions, issues, and feature requests are welcome! Feel free to fork the repository and submit a pull request.

💡 **Built with ❤️ by [Piyush Agrawal](https://github.com/piyush2602)**
