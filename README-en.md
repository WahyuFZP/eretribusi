# E-Retribusi System

An advanced electronic retribution/billing management system built with Laravel, designed for managing company bills, payments, and invoices with automated recurring billing capabilities.

## 🌟 Features

### Bill Management
- **Automated Recurring Bills**: Set up monthly or yearly recurring bills
- **Payment Tracking**: Comprehensive payment history and status monitoring
- **Bill Generation**: Manual and automatic bill generation with scheduling
- **Due Date Management**: Automated reminders and overdue tracking

### Company Management
- **Multi-company Support**: Manage multiple companies with unique billing profiles
- **Company Profiles**: Complete business information including address, phone, email
- **Business Categories**: Support for different types of businesses (badan usaha, jenis usaha)

### Invoice & Payment System
- **Invoice Generation**: Professional invoice creation with PDF export
- **Payment Gateway Integration**: Midtrans payment gateway support
- **Payment History**: Complete audit trail of all transactions
- **Multiple Payment Methods**: Support for various payment channels

### User Management & Security
- **Role-based Access Control**: Admin and user roles with proper permissions
- **Authentication**: Secure login system with Laravel Breeze
- **Data Protection**: Soft deletes and proper data validation

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL/MariaDB
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/your-username/eretribusi.git
cd eretribusi
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install frontend dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
Edit your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eretribusi
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Configure Midtrans (Payment Gateway)**
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

7. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

8. **Build frontend assets**
```bash
npm run build
# or for development
npm run dev
```

9. **Start the application**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 📋 Usage

### Creating Recurring Bills

1. Navigate to **Admin > Bills > Create Bill**
2. Fill in the bill details (company, amount, due date)
3. Check **"Enable Automatic Recurring Bills"**
4. Select frequency (Monthly/Yearly)
5. Save the bill

### Managing Recurring Bills

- Access **Admin > Bills > Manage Recurring** to view all recurring bills
- Generate bills manually or let the system auto-generate
- View next billing dates and modify settings
- Activate/deactivate recurring bills

### Dashboard Features

- Monitor overdue bills and upcoming payments
- Quick actions for bill generation
- Payment status overview
- Company performance metrics

## 🛠️ Technical Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: MySQL with Eloquent ORM
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **PDF Generation**: DomPDF

### Frontend
- **CSS Framework**: Tailwind CSS
- **UI Components**: DaisyUI
- **Build Tool**: Vite
- **JavaScript**: Alpine.js
- **Animations**: AOS (Animate On Scroll)

### Payment Integration
- **Gateway**: Midtrans
- **Methods**: Credit Card, Bank Transfer, E-Wallet

### Development Tools
- **Testing**: Pest PHP
- **Code Quality**: Laravel Pint
- **Development Environment**: Laravel Sail
- **Package Management**: Composer & npm

## 🔧 Artisan Commands

### Recurring Bills Management
```bash
# Generate recurring bills
php artisan bills:generate-recurring

# Preview bills to be generated (dry run)
php artisan bills:generate-recurring --dry-run

# Check upcoming bills
php artisan bills:check-upcoming
```

### Development Commands
```bash
# Run tests
php artisan test
# or
./vendor/bin/pest

# Clear caches
php artisan optimize:clear

# Generate documentation
php artisan route:list
```

## 📂 Project Structure

```
app/
├── Console/Commands/     # Artisan commands
├── Http/
│   ├── Controllers/     # Application controllers
│   └── Requests/       # Form request validation
├── Models/             # Eloquent models
│   ├── Bill.php       # Bill management
│   ├── Company.php    # Company profiles
│   ├── Invoice.php    # Invoice system
│   ├── Payment.php    # Payment tracking
│   └── User.php       # User management
└── Policies/          # Authorization policies

database/
├── factories/         # Model factories for testing
├── migrations/        # Database schema migrations
└── seeders/          # Database seeders

resources/
├── views/            # Blade templates
├── css/              # Stylesheets
└── js/               # JavaScript files

routes/
├── web.php           # Web routes
├── api.php           # API routes
└── auth.php          # Authentication routes
```

## 🔒 Security Features

- **CSRF Protection**: All forms protected against CSRF attacks
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Blade template engine with automatic escaping
- **Role-based Access**: Granular permission system
- **Soft Deletes**: Data recovery capabilities
- **Input Validation**: Comprehensive form request validation

## ⚠️ Security Best Practices

### Environment Variables
- **Never commit `.env` files** to version control
- **Use `.env.example`** as template without real credentials
- **Regenerate APP_KEY** after cloning: `php artisan key:generate`
- **Use strong passwords** for database and admin accounts

### Production Deployment
- **Enable HTTPS** in production environments
- **Set `APP_ENV=production`** and `APP_DEBUG=false`
- **Use environment-specific** API keys and database credentials
- **Enable rate limiting** for API endpoints
- **Regular security updates** for all dependencies

### API Security
- **Validate Midtrans callbacks** using server key verification
- **Log all payment transactions** for audit trails
- **Use CORS** properly for frontend integrations

## 🚦 Testing

Run the test suite:
```bash
# Run all tests
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Feature/BillTest.php

# Run with coverage
./vendor/bin/pest --coverage
```

## 📈 Performance

- **Caching**: Redis/Memcached support for session and cache
- **Asset Optimization**: Vite for optimized CSS/JS bundling
- **Database Optimization**: Proper indexing and query optimization
- **Queue System**: Background job processing for heavy tasks

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation for API changes
- Use conventional commit messages

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue on GitHub
- Contact the development team
- Check the documentation in `/docs`

## 🏷️ Version History

- **v1.2.0** - Recurring bills feature with automated scheduling
- **v1.1.0** - Payment gateway integration with Midtrans
- **v1.0.0** - Initial release with basic billing system

---

**Built with ❤️ using Laravel Framework**