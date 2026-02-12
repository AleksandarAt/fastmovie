# FastMovie Renesse - Bioscoop Applicatie

Een complete Laravel bioscoop applicatie voor het boeken van filmtickets met admin functionaliteit, QR-code scanning, en PDF tickets.

## 🎬 Functionaliteiten

### Voor Bezoekers
- ✅ Overzicht van alle films met geavanceerde zoek- en filterfunctionaliteit
- ✅ Gedetailleerde filminformatie met trailers
- ✅ Online reservering maken voor vertoningen
- ✅ Stoelen selectie en snacks bestellen
- ✅ Veilige online betaling
- ✅ QR-code tickets ontvangen en downloaden als PDF
- ✅ Persoonlijk reserveringen overzicht

### Voor Beheerders
- ✅ Admin dashboard met statistieken en inzichten
- ✅ Volledige CRUD functionaliteit voor films
- ✅ Overzicht van alle reserveringen per film
- ✅ QR-code scanner voor ticket validatie
- ✅ Statistieken over verkoop en populariteit

## 🎨 Design

De applicatie gebruikt het FastMovie Renesse kleurenschema:
- **Primary Color**: `#FF6000` (Oranje)
- **Secondary Color**: `#454545` (Donkergrijs)
- **Accent Colors**: `#FFA559` en `#FFE6C7`

## 📋 Vereisten

- PHP 8.2 of hoger
- Composer
- Node.js & NPM
- SQLite (of een andere database naar keuze)

## 🚀 Installatie & Setup

### 1. Clone de repository

```bash
git clone https://github.com/AleksandarAt/fastmovie.git
cd fastmovie
```

### 2. Installeer dependencies

```bash
composer install
npm install
```

### 3. Configureer environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configureer database

De applicatie is standaard geconfigureerd voor SQLite. Als je een andere database wilt gebruiken, pas dan de `.env` file aan:

```env
DB_CONNECTION=sqlite
# Indien MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=fastmovie
# DB_USERNAME=root
# DB_PASSWORD=
```

### 5. Maak de database aan (voor SQLite)

```bash
touch database/database.sqlite
```

### 6. Run migraties en seeders

```bash
php artisan migrate --seed
```

Dit creëert de database tabellen en vult ze met demo data:
- 15 realistische films met informatie
- Vertoningen voor de komende 14 dagen
- 1 admin account
- 3 normale gebruikers
- Sample reserveringen

### 7. Link storage

```bash
php artisan storage:link
```

### 8. Build frontend assets

```bash
npm run build
```

### 9. Start de applicatie

```bash
php artisan serve
```

De applicatie is nu beschikbaar op: [http://localhost:8000](http://localhost:8000)

## 👤 Login Credentials

### Admin Account
- **Email**: admin@fastmovie.nl
- **Password**: password

### Normale Gebruikers
- **Email**: jan@example.com, maria@example.com, peter@example.com
- **Password**: password (voor alle accounts)

## 📁 Project Structuur

```
fastmovie/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── MovieController.php
│   │   │   ├── MovieAdminController.php
│   │   │   ├── ReservationController.php
│   │   │   └── ScanController.php
│   │   └── Middleware/
│   │       └── IsAdmin.php
│   └── Models/
│       ├── Movie.php
│       ├── Show.php
│       ├── Reservation.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── MovieSeeder.php
│       ├── ShowSeeder.php
│       ├── UserSeeder.php
│       └── ReservationSeeder.php
├── resources/
│   └── views/
│       ├── movies/
│       │   ├── index.blade.php (Homepage)
│       │   └── show.blade.php (Film details)
│       ├── reservations/
│       │   ├── index.blade.php (Mijn reserveringen)
│       │   ├── show.blade.php (Ticket details)
│       │   └── ticket-pdf.blade.php (PDF template)
│       └── admin/
│           ├── dashboard.blade.php
│           ├── scan.blade.php (QR scanner)
│           └── movies/
│               ├── index.blade.php
│               ├── create.blade.php
│               └── edit.blade.php
└── routes/
    └── web.php
```

## 🔐 Beveiligings Features

- ✅ CSRF bescherming op alle forms
- ✅ Admin middleware voor beschermde routes
- ✅ Password hashing met Laravel's bcrypt
- ✅ Authenticatie via Laravel Breeze
- ✅ Unieke ticket codes voor reserveringen
- ✅ QR code validatie voor tickets

## 📦 Gebruikte Packages

- **laravel/breeze** - Authenticatie scaffolding
- **simplesoftwareio/simple-qrcode** - QR code generatie
- **barryvdh/laravel-dompdf** - PDF generatie
- **tailwindcss** - Frontend styling

## 🧪 Testing

Run de tests met:

```bash
php artisan test
```

## 🛠️ Development

Voor development met hot module reloading:

```bash
npm run dev
```

## 📝 Database Schema

### Movies
- title, description, poster_image, trailer_url
- genre, age_rating, language, country
- duration, release_date

### Shows
- movie_id, show_date, show_time
- available_seats, price

### Reservations
- user_id, show_id, seats, seat_numbers (JSON)
- total_price, status (pending/paid/cancelled)
- payment_method, ticket_code (unique)
- snacks (JSON)

### Users
- name, email, password
- is_admin (boolean)

## 🎯 Toekomstige Verbeteringen

- [ ] Email notificaties bij reserveringen
- [ ] Echte payment gateway integratie (Mollie/Stripe)
- [ ] Reviews en ratings systeem
- [ ] Social media integratie
- [ ] Multi-language ondersteuning
- [ ] Geavanceerde stoelen selectie met zaalindeling
- [ ] API endpoints voor mobiele app

## 📄 License

Dit project is open-source software gelicenseerd onder de [MIT license](https://opensource.org/licenses/MIT).

## 👥 Contributors

- Aleksandar At - Initial development

## 📞 Contact

Voor vragen of suggesties, neem contact op via GitHub Issues.

---

Geniet van FastMovie Renesse! 🎬🍿
