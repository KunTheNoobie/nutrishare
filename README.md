# 🌾 NutriShare — Surplus Food Redistribution Platform

> **SDG 2: Zero Hunger** | BMIT3173 Integrative Programming & Technologies

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

---

## 📋 Table of Contents

- [Project Overview](#project-overview)
- [SDG 2 Justification](#sdg-2-justification)
- [Team Members & Module Allocations](#team-members--module-allocations)
- [Architecture & Design Patterns](#architecture--design-patterns)
- [Database Schema (Entity-Relationship)](#database-schema)
- [Security Implementations (OWASP)](#security-implementations)
- [API Documentation (Web Services)](#api-documentation)
- [Setup & Installation](#setup--installation)
- [Git Version Control](#git-version-control)
- [Project Structure](#project-structure)

---

## 🌍 Project Overview

**NutriShare** is a web-based platform that connects surplus food donors (restaurants, supermarkets, caterers) with verified NGOs and charities for efficient food redistribution. The platform directly addresses **UN Sustainable Development Goal 2: Zero Hunger** by minimizing food waste and maximizing food distribution to vulnerable communities.

### Key Features
- 🍲 **Donation Management** — Publish, track, and manage surplus food donations
- ✅ **NGO Verification** — Admin-controlled verification queue for NGO license validation
- 🚚 **Claim & Logistics** — Browse donations, submit claims, assign vehicles, generate collection receipts
- 📦 **Inventory & Food Safety** — Register storage locations, manage food items with allergen tracking
- 🔔 **Automated Notifications** — Real-time alerts to NGOs when new donations are published
- 📊 **SDG Impact Tracking** — Distribution logs recording beneficiaries reached

---

## 🎯 SDG 2 Justification

### Problem Statement
Globally, approximately **1.3 billion tonnes** of food is wasted annually while **828 million people** face hunger. This paradox highlights the need for efficient food redistribution systems.

### How NutriShare Addresses SDG 2
1. **Reduces Food Waste** — Donors can quickly publish surplus food before expiry
2. **Efficient Distribution** — Verified NGOs claim and collect donations with logistics support
3. **Traceability** — Collection receipts and distribution logs ensure accountability
4. **Impact Measurement** — SDG distribution logs track beneficiaries reached, quantities distributed
5. **Trust System** — Reviews and verification ensure food safety standards are maintained

---

## 👥 Team Members & Module Allocations

| Module | Member | Responsibilities |
|--------|--------|-----------------|
| **Module 1**: Donation & Notification | **Liew Yi Ler** | Publish/update donations, Observer Pattern, SQLi & XSS prevention |
| **Module 2**: NGO Verification & Trust | **Cheon Jie Han** | Registration, Factory Method Pattern, Bcrypt & Session security |
| **Module 3**: Claim & Logistics | **Hiew Li Wei** | Claims, State Pattern, IDOR & CSRF prevention |
| **Module 4**: Inventory & Food Safety | **Wong Men Jing** | Inventory, Strategy Pattern, Log Injection & Parameter Tampering prevention |

---

## 🏗️ Architecture & Design Patterns

### MVC Architecture
NutriShare strictly follows the **Model-View-Controller** architectural pattern:
- **Models** (15 Eloquent models) — `app/Models/` — Entity logic and Eloquent ORM relationships
- **Views** (15+ Blade templates) — `resources/views/` — Bootstrap 5 responsive UI
- **Controllers** (8 controllers) — `app/Http/Controllers/` — Business logic and request handling

### Design Patterns Implemented (4)

#### 1. Observer Pattern (Module 1)
- **Location**: `app/Observers/DonationObserver.php`, `app/Contracts/DonationObserverInterface.php`
- **Purpose**: When a `Donation` is created (Subject), the `DonationObserver` (Observer) automatically notifies all verified NGO users and creates system log entries
- **Registration**: `App\Providers\EventServiceProvider::boot()`

#### 2. Factory Method Pattern (Module 2)
- **Location**: `app/Services/UserFactory/`
- **Classes**: `UserCreator` (Abstract), `DonorCreator`, `NgoCreator`, `AdminCreator` (Concrete)
- **Purpose**: Dynamically instantiates the correct user role logic during registration. Each creator sets role-specific defaults, verification status, and post-creation actions.
- **Usage**: `UserCreator::resolve($role)->createUser($data)`

#### 3. State Pattern (Module 3)
- **Location**: `app/States/Claim/`
- **Classes**: `ClaimState` (Abstract), `PendingState`, `ApprovedState`, `CollectedState` (Concrete)
- **Purpose**: Manages the claim lifecycle with valid state transitions:
  ```
  PendingState  →  ApprovedState  →  CollectedState
       ↓               
   RejectedState (terminal)
  ```
- **Usage**: `$claim->transitionTo('approve')`

#### 4. Strategy Pattern (Module 4)
- **Location**: `app/Strategies/Notification/`
- **Classes**: `NotificationStrategyInterface`, `EmailStrategy`, `SMSStrategy`, `NotificationDispatcher`
- **Purpose**: Pluggable notification dispatch — the system dynamically selects Email or SMS strategy based on user preference or urgency level.
- **Usage**: `(new NotificationDispatcher())->dispatch($user, $subject, $message)`

---

## 🗄️ Database Schema

### Entity Models (15 + 1 Pivot)

| # | Entity | Module | Key Relationships |
|---|--------|--------|-------------------|
| 1 | `User` | All | HasMany: Donations, Claims, Reviews, VerificationDocuments, Notifications, SystemLogs, Reports, InventoryLocations |
| 2 | `VerificationDocument` | M2 | BelongsTo: User, Reviewer (User) |
| 3 | `Review` | M2 | BelongsTo: Reviewer (User), Reviewee (User) |
| 4 | `Donation` | M1 | BelongsTo: User; HasMany: Claims, FoodItems, Notifications |
| 5 | `NotificationTemplate` | M1 | HasMany: Notifications |
| 6 | `Notification` | M1 | BelongsTo: User, NotificationTemplate, Donation |
| 7 | `SystemLog` | M1/M4 | BelongsTo: User |
| 8 | `Report` | M1 | BelongsTo: User |
| 9 | `Claim` | M3 | BelongsTo: Donation, User; HasOne: Vehicle, CollectionReceipt; HasMany: DistributionLogs |
| 10 | `Vehicle` | M3 | BelongsTo: Claim |
| 11 | `CollectionReceipt` | M3 | BelongsTo: Claim |
| 12 | `DistributionLog` | M3 | BelongsTo: Claim |
| 13 | `InventoryLocation` | M4 | BelongsTo: User; HasMany: FoodItems |
| 14 | `FoodItem` | M4 | BelongsTo: Donation, InventoryLocation, Category; BelongsToMany: AllergenTags |
| 15 | `Category` | M4 | HasMany: FoodItems |
| 16 | `AllergenTag` | M4 | BelongsToMany: FoodItems |
| — | `allergen_tag_food_item` | M4 | Pivot table for Many-to-Many |

---

## 🔒 Security Implementations

### 8 Secure Coding Practices (2 per Module, Mapped to OWASP)

| # | Module | Vulnerability | OWASP | Implementation | File |
|---|--------|--------------|-------|----------------|------|
| 1 | M1 | SQL Injection | A03 | Eloquent parameterized queries + explicit PDO parameter binding | `app/Repositories/DonationRepository.php` |
| 2 | M1 | Stored XSS | A07 | Context-aware output escaping via Blade `{{ }}` syntax | All `.blade.php` views |
| 3 | M2 | Weak Passwords | A02 | Bcrypt 12-round hashing via Laravel `Hash` facade + password complexity rules | `config/hashing.php`, `app/Http/Requests/RegisterUserRequest.php` |
| 4 | M2 | Session Hijacking | A07 | HttpOnly, Secure, SameSite=Strict cookies + `session()->regenerate()` on login | `config/session.php`, `app/Http/Controllers/AuthController.php` |
| 5 | M3 | IDOR | A01 | Laravel Policies enforce ownership checks on claims | `app/Policies/ClaimPolicy.php` |
| 6 | M3 | CSRF | A05 | `@csrf` token validation on all state-changing forms | All form Blade views |
| 7 | M4 | Log Injection | A09 | Custom CRLF sanitization helper strips `\r\n` before writing to SystemLog | `app/Helpers/SecurityHelper.php`, `app/Models/SystemLog.php` |
| 8 | M4 | Parameter Tampering | A04 | HMAC signed routes via `URL::signedRoute()` + `'signed'` middleware | `app/Http/Controllers/InventoryController.php`, `routes/web.php` |

---

## 🌐 API Documentation

### Interface Agreement (IFA)

**Every API request must include:**
```json
{
    "requestID": "REQ-unique-id",
    "timestamp": "2024-01-15T10:30:00+08:00"
}
```

**Every API response follows:**
```json
{
    "status": "S|F|E",
    "timestamp": "2024-01-15T10:30:00+08:00",
    "data": { ... },
    "message": "Optional error message"
}
```

Status Codes: **S** = Success, **F** = Failure (client error), **E** = Error (server error)

### Endpoints

#### Module 1 — Exposure: `GET /api/donations/active`
Returns all active, unclaimed, non-expired donations.

**Request:**
```
GET /api/donations/active?requestID=REQ-001&timestamp=2024-01-15T10:30:00+08:00
```

**Response (200):**
```json
{
    "status": "S",
    "timestamp": "2024-01-15T10:30:01+08:00",
    "data": {
        "requestID": "REQ-001",
        "donations": [
            {
                "id": 1,
                "title": "Surplus Bread from Bakery",
                "quantity": 50,
                "unit": "items",
                "pickup_address": "123 Main Street",
                "expiry_date": "2024-01-20T00:00:00+08:00",
                "donor": { "name": "John's Bakery", "organization": null }
            }
        ],
        "total": 1
    }
}
```

#### Module 1 — Consumption: Internal HTTP Client
Calls Module 2's `POST /api/user/verify-ngo` before allowing claims.
- **File**: `app/Http/Controllers/Api/DonationApiController.php`
- **Method**: `verifyNgoBeforeClaim(int $ngoUserId): bool`

#### Module 2 — Exposure: `POST /api/user/verify-ngo`
Validates if an NGO user is approved and has a valid license.

**Request:**
```json
{
    "requestID": "VERIFY-001",
    "timestamp": "2024-01-15T10:30:00+08:00",
    "user_id": 5
}
```

**Response (200):**
```json
{
    "status": "S",
    "timestamp": "2024-01-15T10:30:01+08:00",
    "data": {
        "requestID": "VERIFY-001",
        "is_verified": true,
        "verification_status": "approved",
        "has_valid_license": true,
        "organization_name": "Food Aid Foundation",
        "trust_rating": 4.5
    }
}
```

#### Module 3 — Exposure: `GET /api/claim/details`
Returns claim status and details.

**Request:**
```
GET /api/claim/details?requestID=CLM-001&timestamp=2024-01-15T10:30:00+08:00&claim_id=1
```

**Response (200):**
```json
{
    "status": "S",
    "timestamp": "2024-01-15T10:30:01+08:00",
    "data": {
        "requestID": "CLM-001",
        "claim": {
            "id": 1,
            "status": "approved",
            "current_state": "approved",
            "allowed_actions": ["collect"],
            "donation": { "id": 1, "title": "Surplus Bread" },
            "ngo": { "name": "Food Aid", "organization": "Food Aid Foundation" }
        }
    }
}
```

---

## ⚙️ Setup & Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18 (for frontend assets)
- Git

### Installation Steps

```bash
# 1. Clone the repository
git clone https://github.com/KunTheNoobie/nutrishare.git
cd nutrishare

# 2. Install PHP dependencies
composer install

# 3. Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
# DB_DATABASE=nutrishare
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Create the MySQL database
mysql -u root -e "CREATE DATABASE nutrishare;"

# 6. Run migrations and seed the database
php artisan migrate --seed

# 7. Create storage symlink
php artisan storage:link

# 8. Start the development server
php artisan serve
```

The application will be available at **http://localhost:8000**

### Default Test Accounts
After seeding, you can register new accounts via the registration form with roles: **Donor**, **NGO**, or create an admin via tinker:
```bash
php artisan tinker
> App\Services\UserFactory\UserCreator::resolve('admin')->createUser(['name'=>'Admin','email'=>'admin@nutrishare.com','password'=>'Admin@123']);
```

### Testing API Endpoints
```bash
# Get active donations
curl "http://localhost:8000/api/donations/active?requestID=TEST-001&timestamp=2024-01-15T10:00:00Z"

# Verify NGO status
curl -X POST http://localhost:8000/api/user/verify-ngo \
  -H "Content-Type: application/json" \
  -d '{"requestID":"TEST-002","timestamp":"2024-01-15T10:00:00Z","user_id":1}'

# Get claim details
curl "http://localhost:8000/api/claim/details?requestID=TEST-003&timestamp=2024-01-15T10:00:00Z&claim_id=1"
```

---

## 📂 Git Version Control

### Commit History Strategy
The project follows **conventional commits** with granular, meaningful commit messages:

| Commit | Description |
|--------|-------------|
| `chore: initialize Laravel 11 project scaffold` | Project setup, configs, middleware |
| `feat: add database layer` | 18 migrations, 15 models, 6 factories |
| `feat: implement 4 design patterns` | Observer, Factory Method, State, Strategy |
| `feat: add security mitigations and APIs` | OWASP security, IFA-compliant endpoints |
| `feat: add MVC controllers and views` | 8 controllers, 15+ Blade templates |
| `docs: add comprehensive README` | Full documentation |

### Push to GitHub

```bash
# Add remote repository
git remote add origin https://github.com/KunTheNoobie/nutrishare.git

# Push all commits to main branch
git branch -M main
git push -u origin main
```

---

## 📁 Project Structure

```
nutrishare/
├── app/
│   ├── Contracts/                     # Observer Interface
│   │   └── DonationObserverInterface.php
│   ├── Helpers/                       # Security utilities
│   │   └── SecurityHelper.php         # CRLF sanitization + IFA format
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                   # RESTful API controllers
│   │   │   │   ├── DonationApiController.php
│   │   │   │   ├── UserVerificationApiController.php
│   │   │   │   └── ClaimApiController.php
│   │   │   ├── AuthController.php     # Login/Register
│   │   │   ├── DashboardController.php
│   │   │   ├── DonationController.php # Module 1
│   │   │   ├── VerificationController.php # Module 2
│   │   │   ├── ClaimController.php    # Module 3
│   │   │   └── InventoryController.php # Module 4
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php          # Role-based access
│   │   │   ├── EnsureNgoVerified.php  # NGO verification check
│   │   │   └── VerifyCsrfToken.php    # CSRF protection
│   │   └── Requests/                  # Form validation
│   ├── Models/                        # 15 Eloquent models
│   ├── Observers/                     # Observer Pattern
│   │   └── DonationObserver.php
│   ├── Policies/                      # IDOR prevention
│   │   ├── ClaimPolicy.php
│   │   └── DonationPolicy.php
│   ├── Repositories/                  # Data access layer
│   │   └── DonationRepository.php     # PDO parameter binding
│   ├── Services/UserFactory/          # Factory Method Pattern
│   │   ├── UserCreator.php
│   │   ├── DonorCreator.php
│   │   ├── NgoCreator.php
│   │   └── AdminCreator.php
│   ├── States/Claim/                  # State Pattern
│   │   ├── ClaimState.php
│   │   ├── PendingState.php
│   │   ├── ApprovedState.php
│   │   └── CollectedState.php
│   └── Strategies/Notification/       # Strategy Pattern
│       ├── NotificationStrategyInterface.php
│       ├── EmailStrategy.php
│       ├── SMSStrategy.php
│       └── NotificationDispatcher.php
├── config/                            # Laravel configuration
├── database/
│   ├── factories/                     # 6 model factories
│   ├── migrations/                    # 18 migration files
│   └── seeders/
├── resources/views/                   # 15+ Blade templates
│   ├── layouts/app.blade.php
│   ├── auth/ (login, register)
│   ├── donations/ (index, create, show, edit)
│   ├── claims/ (browse, index, show)
│   ├── verification/ (index, reviews)
│   └── inventory/ (index, create, show)
├── routes/
│   ├── web.php                        # Web routes (all 4 modules)
│   └── api.php                        # API routes (IFA endpoints)
└── README.md
```

---

## 📜 License

This project is developed for academic purposes as part of the **BMIT3173 Integrative Programming & Technologies** course.

---

**Built with ❤️ for SDG 2: Zero Hunger**
