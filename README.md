# 📚 FileStore - Digital PDF Book Marketplace

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

A modern digital marketplace for PDF books built with Laravel. Purchase, download, and manage your digital books seamlessly.

## ✨ Features

### 🏠 Frontend Features
- **Home Page** with customer satisfaction highlights
- Dynamic **Admin-configurable menus** (Home, Products, FAQ, About Us)
- **8 Best-selling products** showcase
- **Categories section** with product counts
- **Advanced search** functionality across products
- **Footer** with site information

### 👤 User Features
- **Shopping Cart** (requires authentication)
- **Duplicate purchase prevention** for owned products
- **Discount code system**
- **Wallet system** with partial payments
- **User Dashboard** with:
  - Order history
  - Purchased file downloads
  - Ticket submission
  - Profile management
  - Password change
  - Wallet recharge

### ⚡ Admin Panel
- **Dashboard** with:
  - Sales analytics
  - Revenue tracking
  - Sales charts
- **Management Sections**:
  - Products management
  - File management
  - Categories
  - Tags
  - Discount codes
  - Comments moderation
  - Menu settings
  - Payments
  - Tickets
  - FAQ management

### 🔐 Authentication System
- **Three Login Methods**:
    - Email & Password
    - 6-digit code via Email
    - 6-digit code via SMS

### 💳 Payment Gateway
- **ZarinPal** integration for secure payments

### 📧 Notifications
- Automated purchase confirmation emails

## 🚀 Quick Start

### Prerequisites
- PHP 8.0+
- Composer
- MySQL 5.7+
- Node.js & NPM

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/filestore.git
cd filestore
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Build frontend assets**
```bash
npm run build
```

5. **Environment Setup**
```bash
cp .env.example .env
```

6. **Configure Environment Variables**
Edit `.env` file with your configuration:

```env
# Basic App Configuration
APP_NAME=FileStore
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=filestore
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Email Configuration (Must be configured by user)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=your_smtp_port
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@filestore.com
MAIL_FROM_NAME="FileStore"

# SMS Configuration (Kaveh Negar)
KAVEHNEGAR_API_KEY=your_kavehnegar_api_key
KAVEHNEGAR_SENDER=your_sender_number

# Payment Gateway (ZarinPal)
ZARINPAL_MERCHANT_ID=your_zarinpal_merchant_id
ZARINPAL_SANDBOX=true

# File Upload Configuration
FILESYSTEM_DISK=public
MAX_FILE_SIZE=30720
ALLOWED_FILE_TYPES=pdf
```

7. **Generate application key**
```bash
php artisan key:generate
```

8. **Run database setup**
```bash
php artisan migrate --seed
```

9. **Create storage link**
```bash
php artisan storage:link
```

10. **Start development server**
```bash
composer run dev
```

## 📸 Screenshots

### Home Page
<img src="https://raw.githubusercontent.com/Arash-abraham/File-Store/refs/heads/main/img/Screenshot%20from%202025-10-18%2015-34-53.png">

*Main landing page with customer satisfaction section, best-selling products, and categories*

### Products Page
<img src="https://raw.githubusercontent.com/Arash-abraham/File-Store/refs/heads/main/img/Screenshot%20from%202025-10-18%2015-36-36.png">

*Product catalog with advanced search and category filtering*

### Category View
<img src="https://raw.githubusercontent.com/Arash-abraham/File-Store/refs/heads/main/img/Screenshot%20from%202025-10-18%2015-39-44.png">

*Category page showing product count and filtered results*

### User Dashboard
<img src="https://raw.githubusercontent.com/Arash-abraham/File-Store/refs/heads/main/img/Screenshot%20from%202025-10-18%2015-42-57.png">

*User panel => wallet management*

### Admin Panel
<img src="https://raw.githubusercontent.com/Arash-abraham/File-Store/refs/heads/main/img/Screenshot%20from%202025-10-18%2014-47-05.png">

*Administrative dashboard with analytics and management sections*

### Shopping Cart
<img src="https://github.com/Arash-abraham/File-Store/blob/main/img/Screenshot%20from%202025-10-18%2015-50-01.png">

*Shopping cart with discount codes and wallet integration*

## 🗃️ Database Structure

### Main Tables
- `users` - User accounts and profiles
- `products` - PDF book products
- `categories` - Product categories
- `orders` - Purchase records
- `order_items` - Individual order items
- `wallets` - User wallet balances
- `discount_codes` - Promotional codes
- `tickets` - Support tickets
- `faqs` - Frequently asked questions
- `menus` - Dynamic menu management
- `payments` - Payment transactions
- `comments` - User comments and reviews

## 🔧 Configuration

### Email Setup
You must configure your email service in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=your_smtp_port
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
```

### SMS Setup (Kaveh Negar)
1. Register at [Kaveh Negar](https://kavenegar.com)
2. Get your API key from dashboard
3. Configure in `.env`:
```env
KAVEHNEGAR_API_KEY=your_api_key_here
KAVEHNEGAR_SENDER=your_sender_number
```

### Payment Gateway (ZarinPal)
1. Register at [ZarinPal](https://zarinpal.com)
2. Get your Merchant ID
3. Configure in `.env`:
```env
ZARINPAL_MERCHANT_ID=your_zarinpal_merchant_id
```

### File Upload Configuration
Admins can upload files up to 30MB:
```env
MAX_FILE_SIZE=30720
ALLOWED_FILE_TYPES=pdf
```

## 🚀 Deployment

### Production Deployment Steps:

1. **Environment Setup**
```bash
cp .env.example .env
# Update production values in .env
```

2. **Optimize Application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Set proper permissions**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## 🐛 Troubleshooting

### Common Issues:

**File upload fails:**
- Check `upload_max_filesize` in php.ini (should be at least 30M)
- Verify storage permissions
- Ensure allowed file types in config

**Email not sending:**
- Verify SMTP credentials in `.env`
- Check spam folder
- Test with Mailtrap first

**SMS not working:**
- Verify Kaveh Negar API key
- Check account balance
- Validate sender number

**Payment issues:**
- Verify ZarinPal Merchant ID
- Check callback URLs
- Ensure SSL certificate for production

**Login issues:**
- Check email/SMS configuration
- Verify database connections
- Check session configuration

## 👥 Authors

| **Arash Ebrahimian** | **Amir Hossein Hosseinzade** |
|:---------------------|---------------------:|
| <img src="https://github.com/Arash-abraham.png" width="120" style="border-radius: 50%; border: 3px solid #007bff;"> | <img src="https://github.com/iamhosseinzadeh.png" width="120" style="border-radius: 50%; border: 3px solid #28a745;"> |
| *Lead Developer & Project Architect* | *Co-developer & Template Specialist* |
| [![GitHub](https://img.shields.io/badge/🚀_GitHub-000?style=flat&logo=github)](https://github.com/Arash-abraham) | [![GitHub](https://img.shields.io/badge/🚀_GitHub-000?style=flat&logo=github)](https://github.com/iamhosseinzadeh) |

## 🙏 Acknowledgments

- Kaveh Negar for SMS service
- ZarinPal for payment gateway

## 📞 Support

For support, email arashebi777@gmail.com .

---

<div align="center">
Made with ❤️ using Laravel & JavaScript and Tailwind CSS
</div>
