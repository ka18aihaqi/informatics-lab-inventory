<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/ka18aihaqi/inventory-qr-code/actions">
    <img src="https://github.com/ka18aihaqi/inventory-qr-code/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/ka18aihaqi/inventory-qr-code">
    <img src="https://img.shields.io/packagist/dt/ka18aihaqi/inventory-qr-code" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/ka18aihaqi/inventory-qr-code">
    <img src="https://img.shields.io/packagist/v/ka18aihaqi/inventory-qr-code" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/ka18aihaqi/inventory-qr-code">
    <img src="https://img.shields.io/packagist/l/ka18aihaqi/inventory-qr-code" alt="License">
  </a>
</p>

## About Inventory Website with QR Code Integration

This project is a **web-based inventory management system** developed as my final project at Telkom University. It uses **Laravel, MySQL, JavaScript, and QR Code integration** to streamline asset management and tracking.

**Key Features:**
- **Login system** for secure user access.
- **Input asset data**, including detailed information and categorization.
- **Asset allocation** and assignment to specific locations.
- **Transfer assets** between different locations to maintain real-time inventory status.
- **QR Code identification** to easily track and identify specific tables or locations within the system.

**Live Demo:** [https://informaticslabstelu.com](https://informaticslabstelu.com)

## Technologies Used

- Laravel
- MySQL
- JavaScript
- QR Code Integration

## 📸 Screenshots

### 🧾 Dashboard
![Dashboard](screenshots/dashboard.png)

### 📝 Form Input
![Form Input](screenshots/form-input.png)

### 📄 Asset Allocation
![Export PDF](screenshots/asset-alocation.png)

### 📄 Computer Spescfications
![Export PDF](screenshots/computer-specifications.png)

### 📄 Form Transfer
![Export PDF](screenshots/form-transfer.png)

## Getting Started

To set up the project locally, follow these steps:

```bash
# Clone the repository
git clone https://github.com/ka18aihaqi/inventory-qr-code.git

# Install dependencies
composer install
npm install && npm run dev

# Setup environment variables
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start the development server
php artisan serve
