# FileStore - Digital PDF File Marketplace

## 📖 About The Project
FileStore is a modern, fully-featured digital file marketplace built with Laravel where users can purchase and download PDF files. This platform offers a seamless shopping experience with advanced features for both customers and administrators.

## ✨ Features

### 🏠 Frontend Features
- **Home Page**: 
  - Main section with customer satisfaction highlights
  - Diverse product showcase
  - User testimonials section
  - Online support information
  - 8 best-selling files display
  - Categories section
  - Footer with site information

- **Advanced Search**: 
  - Comprehensive file search functionality
  - Category-based filtering
  - File count display per category

### 👤 User Features
- **Shopping Cart**: 
  - Requires user authentication to add items
  - Prevents duplicate purchases of owned files

- **User Dashboard**:
  - Order history and tracking
  - Download purchased PDF files
  - Ticket submission system
  - Profile management
  - Password change functionality
  - Wallet management system

- **Wallet & Payments**:
  - Digital wallet integration
  - Partial payments (e.g., pay $5 via wallet, $5 via gateway for $10 file)
  - Discount code system

### 🔐 Authentication System
- **Three Login Methods**:
  1. Traditional email/password login
  2. 6-digit code via email
  3. 6-digit code via SMS

### ⚡ Admin Panel
- **Dashboard**:
  - Sales analytics and metrics
  - Revenue tracking
  - Sales charts and graphs
  - File sales statistics

- **Management Sections**:
  - File management
  - File uploads and management
  - Category management
  - Tag management
  - Discount code management
  - Comment moderation
  - Menu configuration
  - Payment management
  - Ticket management
  - FAQ management

### 📧 Notifications
- Automated purchase confirmation emails
- Order status updates
- Promotional communications

## 🛠️ Technology Stack

- **Backend**: Laravel PHP Framework
- **Frontend**: 
  - Tailwind CSS
  - JavaScript
- **Database**: MySQL
- **SMS Service**: Kaveh Negar
- **Authentication**: Laravel Auth with multiple methods

## 📦 Installation

### Prerequisites
- PHP 8.0 or higher
- Composer
- MySQL Database
- Node.js & NPM

### Setup Instructions

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/filestore.git
   cd filestore
