# 📚 FileStore - Digital PDF Book Marketplace

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
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

# File Upload Configuration
FILESYSTEM_DISK=public
MAX_FILE_SIZE=10240
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
php artisan serve
```

## 📸 Screenshots

### Home Page
```https://screenshots/home.png```
*Main landing page with customer satisfaction section, best-selling products, and categories*

### Products Page
"https://screenshots/products.png"
*Product catalog with advanced search and category filtering*

### Category View
"https://screenshots/category.png"
*Category page showing product count and filtered results*

### User Dashboard
"https://screenshots/dashboard.png"
*User panel with orders, downloads, tickets, and wallet management*

### Admin Panel
"https://screenshots/admin.png"
*Administrative dashboard with analytics and management sections*

### Shopping Cart
"https://screenshots/cart.png"
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
"env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=your_smtp_port
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
"

### SMS Setup (Kaveh Negar)
1. Register at [Kaveh Negar](https://kavenegar.com)
2. Get your API key from dashboard
3. Configure in `.env`:
"env
KAVEHNEGAR_API_KEY=your_api_key_here
KAVEHNEGAR_SENDER=your_sender_number
"

## 🚀 Deployment

### Production Deployment Steps:

1. **Environment Setup**
"bash
cp .env.example .env
# Update production values in .env
"

2. **Optimize Application**
"bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
"

3. **Set proper permissions**
"bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
"

4. **Configure web server (Nginx example)**
"nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/filestore/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
"

## 🐛 Troubleshooting

### Common Issues:

**File upload fails:**
- Check `upload_max_filesize` in php.ini
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

**Login issues:**
- Check email/SMS configuration
- Verify database connections
- Check session configuration

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 👥 Authors

- Your Name - *Initial work* - [YourUsername](https://github.com/yourusername)

## 🙏 Acknowledgments

- Laravel Community
- Tailwind CSS Team
- Kaveh Negar for SMS service
- All contributors and testers

## 📞 Support

For support, email support@filestore.com or create a ticket in the support section of the application.

---

<div align="center">
Made with ❤️ using Laravel & Tailwind CSS
</div>
