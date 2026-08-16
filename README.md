# 🌾 NutriShare — Surplus Food Redistribution Platform

> **UN SDG 2: Zero Hunger | Web Application Development & Software Architecture**

NutriShare is a state-of-the-art web application engineered to bridge surplus food donors (supermarkets, bakeries, hotels, restaurants) with verified Non-Governmental Organizations (NGOs), reducing food waste while empowering vulnerable communities under **UN Sustainable Development Goal 2 (Zero Hunger)**.

---

## 🚀 Key System Features & Modules

### Module 1: Donation & Notification Management (Liew Yi Ler)
- **Surplus Food Publishing:** Donors publish available food donations with photos, quantity, unit, pickup location, map coordinates, and expiry dates.
- **Multi-Image Support:** Up to 5 high-resolution photos or image URLs per donation item.
- **Event-Driven Notifications:** Real-time system & email alerts dispatched via the **Observer Pattern** when donations are published, claimed, or updated.

### Module 2: NGO Verification & Peer Trust Rating System (Cheon Jie Han)
- **Document Verification:** NGOs upload registration certificates, tax exemption docs, and food premise licenses for Admin/Moderator approval with custom remarks.
- **Trust & Peer Review System:** Donors and NGOs leave 1–5 star trust ratings and reviews post-collection to foster platform credibility.
- **OTP Password Reset:** 3-step secure 6-digit OTP verification code flow for password resets.

### Module 3: Claims & Logistics Distribution (Yap Zhing Shuen)
- **State-Driven Claims:** **State Pattern** manages claim lifecycles (`pending` ➔ `approved` ➔ `collected`, or `rejected` / `cancelled`).
- **Logistics & Dispatch:** Assign pickup vehicles (van, truck, car, motorcycle) and driver contact details.
- **Digital Collection Receipts:** Auto-generated unique receipts (`REC-NUTRI-YYYYMMDD-XXX`) with one-click **Print Receipt** functionality.
- **SDG 2 Impact Tracking:** Record distribution logs detailing beneficiaries count, distribution center, and quantity distributed.

### Module 4: Inventory & Food Safety Compliance (Wong Men Jing)
- **Multi-Location Storage:** NGOs manage dry, cold, ambient, and blast freezer inventory facilities with live capacity tracking and CSV exports.
- **Allergen & Expiry Tracking:** Food items tagged with allergen warnings (Gluten, Dairy, Nuts, Soy, Egg, Seafood) and automatic expiry countdowns.

---

## 📊 Live Analytics & Presenter Highlights

- **📊 Interactive Chart.js Analytics Graphs:** Real-time **Food Rescue Category Bar Chart** & **Claim Status Ratio Doughnut Chart** rendered automatically on the Dashboard (`/dashboard`).
- **🌍 Role-Specific UN SDG 2 Impact Tracker:** Live metrics detailing **Food Rescued (kg)**, **Beneficiaries Fed (People)**, and **CO₂e Environmental Savings (Tons)** personalized for Donors, NGOs, and Admins.
- **🎭 1-Click Presentation Demo Switcher:** Top navbar dropdown & login page buttons to switch between **Admin**, **Moderator**, **NGO**, and **Donor** in 1 second during live demos (`/demo-login/{role}`).
- **📥 CSV Data Exporters:** Download CSV files for Donations Catalog (`/donations/export/csv`), System Audit Logs (`/logs/export/csv`), and Inventory Storage Facilities (`/inventory/export/csv`).
- **🖨️ Printable Collection Receipts:** One-click receipt print preview layout for physical driver sign-off.

---

## 🔒 Role-Based Access Control (RBAC) Matrix

| System Feature / Action | **Admin** | **Moderator** | **Donor** | **NGO** |
|---|:---:|:---:|:---:|:---:|
| **Publish / Edit Donations** | ✅ | ✅ | ✅ (Own) | ❌ |
| **Delete Donations** | ✅ | ❌ | ✅ (Own) | ❌ |
| **Submit Claims** | ❌ | ❌ | ❌ | ✅ (Verified) |
| **Approve / Reject Claims** | ✅ | ✅ | ✅ (Own) | ❌ |
| **Collect Claims & Log SDG Impact** | ✅ | ✅ | ❌ | ✅ (Own) |
| **Delete Claims** | ✅ | ❌ | ❌ | ✅ (Pending) |
| **Manage Inventory & Storage Facilities** | ✅ | ✅ | ❌ | ✅ (Own) |
| **Export CSV Data (Donations / Inventory)** | ✅ | ✅ | ✅ (Own Catalog) | ✅ (Own Inventory) |
| **Review NGO Verification Docs** | ✅ | ✅ | ❌ | Upload Only |
| **Generate Platform Analytics Reports** | ✅ | ✅ | ❌ | ❌ |
| **View Audit Trail & Export Security Logs CSV** | ✅ | ✅ | ❌ | ❌ |

---

## 🔑 Creating Admin & Moderator Accounts

You can create administrative accounts at any time via the custom NutriShare Artisan CLI command:

```bash
# Create System Admin Account
php artisan nutrishare:create-admin --name="System Admin" --email="admin@example.com" --password="Password1!" --role=admin

# Create Platform Moderator Account
php artisan nutrishare:create-admin --name="Mod Name" --email="mod@example.com" --password="Password1!" --role=moderator
```

---

## 🛠️ Automated System Health & Diagnostic Audit

Run the platform diagnostic command to verify database connectivity, Mailpit status, and 10+ table record targets before your presentation:

```bash
php artisan nutrishare:health-check
```

---

## 🧪 Automated Testing Suite (68 Assertions)

Run the full end-to-end automated test suite across all 4 modules, RBAC gates, Web Services, and CSV exporters:

```bash
php artisan test
```

> **Note:** Tests automatically execute on a dedicated, isolated test database (`nutrishare_testing`) configured in `phpunit.xml`. Running tests will **never wipe or overwrite** your primary presentation demo database (`nutrishare`).

---

## 🌐 Web Services Architecture (IFA Compliance)

All 4 modules expose and consume RESTful web services strictly following the **Interface Agreement (IFA)** standard:
- **IFA Request Schema:** `{ "requestID": "REQ-...", "timestamp": "ISO-8601", ... }`
- **IFA Response Schema:** `{ "status": "S|F|E", "timestamp": "ISO-8601", "data": { ... } }`

| Module | Team Member | HTTP Method & Endpoint | Description | Status |
|---|---|---|---|:---:|
| **Module 1** | Liew Yi Ler | `GET /api/donations/active` | Exposes active, non-expired surplus food listings | ✅ Operational |
| **Module 2** | Cheon Jie Han | `POST /api/user/verify-ngo` | Validates NGO legal certification & licensing | ✅ Operational |
| **Module 3** | Yap Zhing Shuen | `GET /api/claim/details` | Exposes real-time claim lifecycle & logistics state | ✅ Operational |
| **Module 4** | Wong Men Jing | `GET /api/inventory/status` | Exposes storage capacity & occupancy breakdown | ✅ Operational |
| **Module 4** | Wong Men Jing | `POST /api/inventory/food-safety-check` | Verifies allergen safety & expiry thresholds | ✅ Operational |

---

## 🏗️ Software Architecture & Design Patterns

### 1. Factory Method Pattern (Module 2 — User Creation)
- **Abstract Creator:** `UserCreator`
- **Concrete Creators:** `AdminCreator`, `ModeratorCreator`, `DonorCreator`, `NgoCreator`
- Encapsulates role-specific defaults, verification statuses, and automated post-creation audit logging.

### 2. State Pattern (Module 3 — Claim Lifecycle)
- **State Interface:** `ClaimStateInterface`
- **Concrete States:** `PendingState`, `ApprovedState`, `CollectedState`, `RejectedState`, `CancelledState`
- Enforces valid state transitions and encapsulates business rules per lifecycle stage.

### 3. Strategy Pattern (Module 4 — Notification Dispatching)
- **Strategy Interface:** `NotificationStrategyInterface`
- **Concrete Strategies:** `EmailStrategy`, `SmsStrategy`
- **Context:** `NotificationDispatcher`
- Dynamically selects communication channels based on user preferences.

### 4. Observer Pattern (Module 1 — Event-Driven Notifications)
- **Observer:** `DonationObserver` & `SendDonationNotificationJob`
- Listens for `created` events on `Donation` model to automatically notify verified NGOs asynchronously via queueable background jobs.

### 5. Repository Pattern (Modules 1, 3 & 4 — Data Layer Abstraction)
- **Repositories:** `DonationRepository`, `ClaimRepository`, `InventoryRepository`
- Encapsulates Eloquent parameterized query logic and aggregations, completely decoupling controllers from raw queries.

---

## 🛡️ Security Implementations (OWASP Compliance)

- **OWASP A01: Broken Access Control:** Enforced via Laravel Policies (`DonationPolicy`, `ClaimPolicy`) and `CheckRole` middleware.
- **OWASP A02: Cryptographic Failures:** Bcrypt password hashing (`ROUND=12`) and signed HMAC URLs for quick-claim actions.
- **OWASP A03: SQL Injection:** Parameterized queries & PDO prepared statements via Eloquent ORM.
- **OWASP A04: Parameter Tampering:** HMAC signed routes (`URL::signedRoute`) for quick-claim actions.
- **OWASP A05: Security Misconfiguration:** CSRF token `@csrf` validation on all HTTP POST/PUT/DELETE forms.
- **OWASP A07: Stored XSS Prevention:** Automatic Blade HTML escaping `{{ }}` across all views.
- **OWASP A09: Security Logging & Monitoring:** CRLF sanitization helper (`SecurityHelper`) preventing log injection in `SystemLog`.
- **Brute-Force Attack Prevention:** `throttle:6,1` rate-limiting middleware applied to `/login` and `/forgot-password/otp/verify`.

---

## 🗄️ Database Architecture (All 26 Tables Breakdown)

The database consists of **18 Application Feature Tables** (10+ to 48+ records each) and **8 Framework Infrastructure Tables**:

| # | Database Table Name | Category | Record Count | Description |
|---|---|---|:---:|---|
| 1 | `users` | Application | 12 | System Admins, Moderators, NGOs, Donors |
| 2 | `donations` | Application | 12 | Surplus food donation listings |
| 3 | `food_items` | Application | 16 | Individual food items with photos & expiry |
| 4 | `categories` | Application | 10 | Food categories (Produce, Bakery, Dairy, etc.) |
| 5 | `allergen_tags` | Application | 10 | Allergen safety tags (Gluten, Dairy, Nuts, etc.) |
| 6 | `allergen_tag_food_item` | Application | 17 | Pivot table linking food items to allergens |
| 7 | `inventory_locations` | Application | 10 | NGO storage facilities (dry, cold, freezer) |
| 8 | `claims` | Application | 10 | NGO claim requests across state lifecycles |
| 9 | `vehicles` | Application | 10 | Logistics vehicles & driver dispatch details |
| 10 | `collection_receipts` | Application | 10 | Digital pickup receipts with driver notes |
| 11 | `distribution_logs` | Application | 10 | SDG 2 Zero Hunger impact tracking logs |
| 12 | `verification_documents` | Application | 10 | NGO registration certs & compliance licenses |
| 13 | `reviews` | Application | 10 | Peer trust ratings (1-5 stars) & reviews |
| 14 | `reports` | Application | 10 | Platform analytics & SDG impact reports |
| 15 | `notification_templates` | Application | 10 | Predefined alert templates |
| 16 | `notifications` | Application | 48 | Dispatched system & email user alerts |
| 17 | `system_logs` | Application | 25 | Audit trail logs with IP & User Agent |
| 18 | `password_reset_otps` | Application | 10 | 6-digit OTP security reset tokens |
| 19 | `sessions` | Framework | Active | Managed automatically by Laravel session driver |
| 20 | `cache` | Framework | System | Framework database query cache |
| 21 | `cache_locks` | Framework | System | Atomic lock manager for cache |
| 22 | `jobs` | Framework | System | Background task queue queue worker table |
| 23 | `job_batches` | Framework | System | Batch job processing queue manager |
| 24 | `failed_jobs` | Framework | System | Failed background queue logger |
| 25 | `migrations` | Framework | 26 | Database schema migration tracking history |
| 26 | `password_reset_tokens` | Framework | System | Legacy auth token table |

---

## 🔑 Demo Login Credentials

All accounts use password: **`Password1!`**

| User Role | Email Address | Access Level |
|---|---|---|
| **System Admin** | `admin@nutrishare.com` | Full CRUD, User Verification, Reports, Audit Logs |
| **Platform Moderator** | `moderator@nutrishare.com` | Oversight (Create/Read/Update, No Delete) |
| **NGO (Primary)** | `ngo@nutrishare.com` | Claims, Inventory, Logistics, SDG Impact Logs |
| **NGO (Secondary)** | `kechara@nutrishare.com` | Claims & Shelter Distribution |
| **Donor (Primary)** | `donor@nutrishare.com` | Publish Donations, Approve Claims, Reviews |
| **Donor (Secondary)** | `jayagrocer@nutrishare.com` | Supermarket Donations |

---

## ⚙️ Quickstart Guide

1. **Clone & Install:**
   ```bash
   git clone https://github.com/KunTheNoobie/nutrishare.git
   cd nutrishare
   composer install
   ```

2. **Wipe & Seed God Tier Database:**
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Start Application Server:**
   ```bash
   php artisan serve
   ```
   *Note: `mailpit.exe` will automatically launch in the background to capture emails at `http://127.0.0.1:8025`.*
