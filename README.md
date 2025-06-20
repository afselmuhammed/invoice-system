# Laravel Invoice Generation System

This project is a high-performance invoice generation and delivery system built with Laravel. It supports bulk invoice generation (10,000+), real-time status updates, PDF generation, email delivery, job queuing, and dashboard monitoring.

---

## ✅ Features

- Bulk invoice generation with queue jobs
- PDF generation and email sending using queues
- Database transactions and retry logic
- Realtime dashboard with status updates
- Pagination support
- View generated invoice PDFs
- Logs, error handling, and memory-safe processing

---

## 🛠️ Technologies Used

- Laravel 12+
- Laravel Queues (`queue:work`)
- Mailables
- Blade Templates
- MySQL
- Storage (PDFs)
- Bootstrap/CSS for frontend

---

## 📦 Installation

1. **Clone the repo**
   ```bash
   git clone https://github.com/your-repo/laravel-invoice-system.git
   cd laravel-invoice-system
Install dependencies

composer install
npm install && npm run dev (optional)

Set up your .env

cp .env.example .env
php artisan key:generate

Configure database

    Update .env with your DB settings

    Migrate and seed data:

    php artisan migrate --seed

Link storage

    php artisan storage:link

    Mail Configuration

    Set MAIL_MAILER, MAIL_HOST, etc. in .env for sending emails

 How to Run
1. Start the Laravel Server

php artisan serve

2. Start the Queue Worker

This is required for processing invoices. (Didnt Used Supervisor)
`php artisan optimize:clear `
`php artisan queue:work`

You may use --tries, --timeout, --memory, etc., for production environments.
📋 How to Use
Landing Page

Go to http://127.0.0.1:8000/ to view the Invoice Dashboard.
Dashboard Features:

    View list of customers with:

        Latest invoice number

        Status: pending, sent, failed

        View PDF link (if generated)

🧪 Testing

To test:

    Ensure queue worker is running

    Press Generate Invoices

    Watch the status change and PDF links appear

    Check storage/logs/laravel.log for failures

📁 Folder Structure Overview

app/
 ├── Jobs/
 │   ├── GenerateInvoicePdf.php
 │   └── SendInvoiceEmailJob.php
 ├── Services/
 │   └── InvoiceGeneratorService.php
resources/views/
 └── invoices/
     └── landing.blade.php
routes/
 └── web.php
