# 🌾 NutriShare — Surplus Food Redistribution Platform

> **UN SDG 2: Zero Hunger | Web Application Development & Software Architecture**

NutriShare is a state-of-the-art web application engineered to bridge surplus food donors (supermarkets, bakeries, hotels, restaurants) with verified Non-Governmental Organizations (NGOs), reducing food waste while empowering vulnerable communities under **UN Sustainable Development Goal 2 (Zero Hunger)**.

---

## 🚀 Key System Features & Modules

### Module 1: Donation & Notification Management
- **Surplus Food Publishing:** Donors publish available food donations with photos, quantity, unit, pickup location, map coordinates, and expiry dates.
- **Multi-Image Support:** Up to 5 high-resolution photos or image URLs per donation item.
- **Event-Driven Notifications:** Real-time system & email alerts dispatched when donations are published, claimed, or updated.

### Module 2: NGO Verification & Peer Trust Rating System
- **Document Verification:** NGOs upload registration certificates, tax exemption docs, and food premise licenses for Admin/Moderator approval.
- **Trust & Peer Review System:** Donors and NGOs leave 1–5 star trust ratings and reviews post-collection to foster platform credibility.
- **OTP Password Reset:** 3-step secure 6-digit OTP verification code flow for password resets.

### Module 3: Claims & Logistics Distribution
- **State-Driven Claims:** State Pattern manages claim lifecycles (`pending` ➔ `approved` ➔ `collected`, or `rejected` / `cancelled`).
- **Logistics & Dispatch:** Assign pickup vehicles (van, truck, car, motorcycle) and driver contact details.
- **Digital Collection Receipts:** Auto-generated unique receipts (`REC-NUTRI-YYYYMMDD-XXX`) with driver sign-off.
- **SDG 2 Impact Tracking:** Record distribution logs detailing beneficiaries count, distribution center, and quantity distributed.

### Module 4: Inventory & Food Safety Compliance
- **Multi-Location Storage:** NGOs manage dry, cold, ambient, and blast freezer inventory facilities with live capacity tracking.
- **Allergen & Expiry Tracking:** Food items tagged with allergen warnings (Gluten, Dairy, Nuts, Soy, Egg, Seafood) and automatic expiry countdowns.

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
| **Manage Inventory & Facilities** | ✅ | ✅ | ❌ | ✅ (Own) |
| **Review NGO Verification Docs** | ✅ | ✅ | ❌ | Upload Only |
| **Generate Platform Analytics Reports** | ✅ | ✅ | ❌ | ❌ |
| **View Audit Trail & System Activity Logs** | ✅ | ✅ | ❌ | ❌ |

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
- **Observer:** `DonationObserver`
- Listens for `created` events on `Donation` model to automatically notify verified NGOs.

### 5. Repository Pattern (Module 1 — Data Access)
- **Repository:** `DonationRepository`
- Encapsulates Eloquent parameterized query logic for searching and filtering donations.

---

## 🛡️ Security Implementations (OWASP Compliance)

- **OWASP A01: Broken Access Control:** Enforced via Laravel Policies (`DonationPolicy`, `ClaimPolicy`) and `CheckRole` middleware.
- **OWASP A02: Cryptographic Failures:** Bcrypt password hashing (`ROUND=12`) and signed HMAC URLs for quick-claim actions.
- **OWASP A03: SQL Injection:** Parameterized queries via Eloquent ORM.
- **OWASP A05: Security Misconfiguration:** CSRF token `@csrf` validation on all HTTP POST/PUT/DELETE forms.
- **OWASP A07: Stored XSS Prevention:** Automatic Blade HTML escaping `{{ }}` across all views.
- **OWASP A09: Security Logging & Monitoring:** CRLF sanitization helper (`SecurityHelper`) preventing log injection in `SystemLog`.

---

## 🗄️ Database Architecture (26 Tables Coverage)

The database consists of **18 Application Feature Tables** (100% populated with 10+ records each) and **8 Framework System Tables**:

### Application Tables (10+ Records Each)
1. `users` (12 records) — Core user accounts across all 4 roles.
2. `donations` (12 records) — Surplus food donation listings.
3. `food_items` (12 records) — Detailed food items linked to donations.
4. `categories` (10 records) — Food classification categories.
5. `allergen_tags` (10 records) — Food safety allergen tags.
6. `allergen_tag_food_item` (17 records) — Pivot table connecting food items to allergens.
7. `inventory_locations` (10 records) — NGO storage facilities.
8. `claims` (10 records) — Claim lifecycle records.
9. `vehicles` (10 records) — Pickup logistics vehicles & drivers.
10. `collection_receipts` (10 records) — Digital pickup receipts.
11. `distribution_logs` (10 records) — SDG 2 Zero Hunger impact logs.
12. `verification_documents` (10 records) — NGO compliance documents.
13. `reviews` (10 records) — Peer trust ratings and feedback.
14. `reports` (10 records) — Platform analytics & SDG impact reports.
15. `notification_templates` (10 records) — System alert templates.
16. `notifications` (48 records) — Dispatched user alerts.
17. `system_logs` (24 records) — Audit logs with IP Address & User Agent.
18. `password_reset_otps` (10 records) — 6-digit OTP security tokens.

### Framework System Tables
19. `sessions` — Browser session management.
20. `cache` & 21. `cache_locks` — Performance query cache.
22. `jobs`, 23. `job_batches`, & 24. `failed_jobs` — Background queue engine.
25. `migrations` — Schema versioning.
26. `password_reset_tokens` — Legacy token storage.

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

## ⚙️ Quickstart & Local Setup Guide

1. **Clone & Install Dependencies:**
   ```bash
   git clone https://github.com/KunTheNoobie/nutrishare.git
   cd nutrishare
   composer install
   ```

2. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Wipe & Seed God Tier Database:**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Start Application Server:**
   ```bash
   php artisan serve
   ```
   *Note: `mailpit.exe` will automatically launch in the background to capture emails at `http://127.0.0.1:8025`.*
