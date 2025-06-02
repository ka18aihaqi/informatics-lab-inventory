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

This project is a **web-based inventory management system** developed as a final project at Telkom University. It leverages **Laravel, MySQL, JavaScript, and QR Code integration** to simplify inventory tracking and asset management.

**Key Features:**
- Asset input, allocation, and tracking
- QR Code integration for seamless item management
- User-friendly interfaces and secure data handling
- Fully tested backend and frontend implementation

**Live Demo:** [https://informaticslabstelu.com](https://informaticslabstelu.com)

## Technologies Used

- Laravel
- MySQL
- JavaScript
- QR Code Integration (for inventory tracking)

## Getting Started

To get a local copy up and running, follow these simple steps:

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
