# SaditaSystem - Working Context for AI Agents

> Sistem Informasi Manajemen Bisnis Sadita
> Package: `twolines-dev/sadita-system`

---

## 1. Product Direction

SaditaSystem is a business management web app for **Sadita Decoration**, focused on:

- `Sewa`: papan bunga, box hantaran, and other rentable items
- `Jasa`: dekorasi
- `Hybrid`: hantaran, because it can be either box-only or box + titip belanja isi

### Important scope decisions

- **Florist / buket bunga is out of scope.**
- This system should not be treated as a full e-commerce checkout-first app.
- The current target flow is closer to **request order -> admin review -> pricing finalization -> payment -> processing -> completion**.
- Customers should **not** be forced to create an account or log in.
- Tracking pesanan will be implemented later, step by step, after the core order flow is connected properly.

### Public vs admin layers

1. **Public website**
   - Landing page
   - Order/request form
   - Later: order tracking without login
2. **Admin panel** (`/admin`)
   - Built with Filament
   - Used for operational management, pricing, payment verification, and order progress

### Team structure

- `Bagatio Putra Joandri` -> Project Manager and AI Specialist
- `Ahsan Ramadan` -> Lead Programmer
- `Jeli Mayora` -> System Analyst
- `Aprilla Maulida` -> Quality Assurance

### Current memory anchor

Use this section as the fastest orientation point after a new chat session or context compaction.

- Product scope is **Sadita Decoration management**, not florist/buket.
- Public customer flow should stay **guest/no-login**.
- Main product direction is **request order -> admin review -> price confirmation -> payment -> processing -> completion**.
- Database target for real local/prod behavior is **MySQL/MariaDB**, commonly through XAMPP for teammates.
- Existing public web is still mostly landing/catalog plus order mock flow; do not assume public order is fully connected to DB.
- Tracking is planned later and should be built incrementally after create-order flow is stable.
- Figma task status: a landing page prototype plan was prepared for file `e8kk1MBxAW7FflDxsd1X2q`, but Figma MCP hit Starter plan call limit before generation completed.
- Recent pushed branches:
  - `feature/optimasi-admin-panel` with commit `ab31b77` (`fix: meringankan query admin panel`)
  - `feature/perbaikan-admin-xampp` with commit `1464339` (`fix: memperbaiki akses menu admin lokal`)
- Local uncommitted docs/schema work may exist:
  - `AGENTS.md`
  - `database/migrations/2026_05_18_110000_extend_order_flow_for_service_types.php`
  - `docs/ERD_mermaid.txt`
  - `docs/ERD Sadita System.png`
  - `download_erd.cjs`
  Treat these as intentional local work unless the user says otherwise.

---

## 2. Core Business Interpretation

### Recommended business flow for V1

Use this mental model unless the user explicitly changes direction:

1. Customer fills form without login
2. System stores a **request pesanan**
3. Admin reviews the request
4. Admin confirms or adjusts pricing
5. Customer pays DP / full / approved amount
6. Admin processes the order
7. Order is delivered / completed
8. For rental orders, continue to return handling if needed

### Hantaran modes

Treat hantaran as two separate operational modes:

#### 1. Hantaran box only

- Sadita provides box and decoration only
- Customer buys/fills the contents themselves
- Price can usually be fixed per box/package
- This mode is suitable for direct pricing from the public form

#### 2. Hantaran titip belanja

- Sadita provides box/decor and also buys the contents on behalf of the client
- Final item cost is **not fixed upfront**
- Do **not** force a final total too early
- Use an estimate-first workflow

### Pricing rule for titip belanja

For variable-cost hantaran, the safest V1 approach is:

1. Customer submits request and item notes / budget
2. Admin creates an estimate
3. Customer pays estimated amount or agreed DP
4. Sadita shops the items
5. Final real cost is reconciled
6. Any difference becomes tambahan tagihan or refund/adjustment

This means the app should support **invoice-style staged payment**, not just one fixed grand total from the start.

---

## 3. Technical Stack

| Layer | Technology | Version |
|---|---|---|
| Backend | Laravel | `^13.0` |
| Admin Panel | Filament | `^5.6` |
| PHP | PHP | `^8.3` |
| Frontend Bundler | Vite | `^8.0` |
| CSS | Tailwind CSS | `^4.0` |
| Database | MySQL | primary local/prod database |
| Testing DB | SQLite in-memory | PHPUnit |
| Deployment | Railway | via `nixpacks.toml` |
| AI Chatbot | Groq API | env-based |

### Key package notes

- Backend dependencies are managed through `composer`
- Frontend dependencies are managed through `npm`
- Tailwind uses the Vite plugin, not a traditional `tailwind.config.js`

---

## 4. Folder Structure

```text
SaditaSystem/
|-- app/
|   |-- Filament/
|   |   |-- Resources/
|   |   |-- Widgets/
|   |-- Http/Controllers/
|   |-- Models/
|   |-- Observers/
|   |-- Policies/
|   |-- Providers/
|   `-- Services/
|-- database/
|   |-- factories/
|   |-- migrations/
|   `-- seeders/
|-- public/
|-- resources/
|   |-- css/
|   |-- js/
|   `-- views/
|-- routes/
|-- storage/
|-- tests/
|-- .github/workflows/
|-- composer.json
|-- package.json
|-- nixpacks.toml
`-- vite.config.js
```

### Important directories

- `app/Filament/Resources`: admin CRUD resources
- `app/Filament/Widgets`: admin dashboard widgets
- `app/Http/Controllers`: public controllers
- `app/Models`: Eloquent models
- `database/migrations`: schema source of truth
- `database/seeders`: dev/master seed data
- `resources/views/pages/home`: public landing and order pages
- `routes/web.php`: public routes
- `app/Providers/Filament/AdminPanelProvider.php`: Filament admin setup

---

## 5. Routes and Access

### Public routes

Defined in `routes/web.php`:

- `GET /` -> landing page
- `GET /order` -> order page
- `GET /login` -> admin login form
- `POST /login` -> login submit
- `POST /logout` -> logout

### Admin routes

- Admin panel base path: `/admin`
- Registered through Filament in `AdminPanelProvider`
- Access is controlled through `User::canAccessPanel()` and `is_admin`

### Customer auth stance

- Customers should remain **guest users**
- Avoid mandatory registration/login for public ordering
- When tracking is added later, prefer lookup by:
  - `kode_pesanan`
  - and optionally phone/WhatsApp validation

---

## 6. Current Domain Models

### Main models

- `User`
- `Kategori`
- `Produk`
- `GambarProduk`
- `Pelanggan`
- `Pesanan`
- `DetailPesanan`
- `Pembayaran`
- `Pengiriman`
- `PengeluaranPesanan`
- `PengembalianPesanan`
- `Ulasan`
- `EmailLog`
- `ActivityLog`

### Important relationship picture

- `Kategori` -> has many `Produk`
- `Produk` -> has many `GambarProduk`
- `Produk` -> has many `DetailPesanan`
- `Produk` -> has many `Ulasan`
- `Pelanggan` -> has many `Pesanan`
- `Pesanan` -> has many `DetailPesanan`
- `Pesanan` -> has many `Pembayaran`
- `Pesanan` -> has one `Pengiriman`
- `Pesanan` -> has many `PengeluaranPesanan`
- `Pesanan` -> has one `PengembalianPesanan`
- `Pesanan` -> has many `Ulasan`
- `Pesanan` -> has many `EmailLog`

### Important modeling direction going forward

When changing the schema, keep this product direction in mind:

- Do not shape all orders like fixed-price retail checkout
- Support both `sewa`, `jasa`, and `hybrid`
- Hantaran titip belanja likely needs breakdown values such as:
  - base package price
  - service fee
  - shopping estimate
  - shopping actual
  - shipping / delivery fee
  - additional cost
  - paid total
  - outstanding balance

If future schema changes are proposed, they should be evaluated against this staged-payment and variable-pricing reality.

---

## 7. Development Commands

### First setup

```bash
composer run setup
```

This runs:

- `composer install`
- copy `.env.example` to `.env` if needed
- `php artisan key:generate`
- `php artisan migrate --force`
- `npm install --ignore-scripts`
- `npm run build`

### Daily development

```bash
composer run dev
```

This starts:

- Laravel dev server
- queue listener
- Laravel Pail
- Vite dev server

### Individual commands

```bash
composer install
npm install

php artisan migrate
php artisan db:seed
php artisan serve

npm run dev
npm run build

composer run format
npm run format

composer run test
php artisan test
```

---

## 8. Database and Environment

### Database stance

- **Use MySQL for actual local development and production behavior**
- PHPUnit may still use SQLite in-memory through `phpunit.xml`
- If documentation says SQLite is the default app database, treat that as outdated unless the user explicitly says otherwise

### Required env areas

```env
APP_NAME="Sadita System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

GROQ_API_KEY=
GROQ_MODEL=
```

### Seeder note

`DatabaseSeeder` creates a demo admin account and runs seeders for master data. Do not assume seeders are fully idempotent in production.

---

## 9. Deployment Notes

### Railway source of truth

- Railway uses `nixpacks.toml`
- `Dockerfile` is not the effective deployment config for Railway

### Important deployment constraints

- Do **not** add `db:seed` to the Railway start command
- Be careful with config caching during build time
- Keep deployment changes aligned with real Railway behavior, not only local Docker assumptions

---

## 10. Git and GitHub Workflow

### Known branch structure

- `main` = production branch
- `develop` = development / integration branch

### Team workflow

Follow this exact team flow unless the user explicitly changes it:

1. Clone the repository
2. Make sure the current base branch is `develop`
3. Run `git pull origin develop`
4. Create a new feature branch from `develop`
5. Work only in that feature branch
6. After finishing, stage the changes
7. Commit using the agreed commit prefix format
8. Push the feature branch to GitHub
9. The branch is then reviewed by the Lead Programmer before being merged into `develop`

### Expected command sequence

```bash
git branch
git checkout develop
git pull origin develop
git checkout -b feature/nama_fitur

# after changes
git add .
git commit -m "feat: deskripsi fitur dalam bahasa indonesia"
git push origin feature/nama_fitur
```

### Branch naming rule

- Prefix stays in English, for example: `feature/...`
- Branch description may use Indonesian or mixed Indonesian-English
- Examples:
  - `feature/form_login_admin`
  - `feature/perbaikan-order-flow`
  - `feature/tracking-pesanan-v1`

### Commit message rule

Use this format:

```text
<prefix>: <deskripsi dalam bahasa indonesia>
```

### Allowed commit prefixes

- `feat`: for new features
- `fix`: for bug fixes
- `chore`: for setup, dependency, or maintenance work
- `docs`: for documentation-only changes
- `style`: for formatting-only changes
- `test`: for tests

### Commit message examples

- `feat: menambahkan form login admin`
- `fix: memperbaiki tombol submit yang tidak berfungsi`
- `chore: menambahkan prettier`
- `docs: memperbarui panduan instalasi`
- `style: merapikan format kode dengan pint`
- `test: menambahkan pengujian login`

### Merge responsibility

- Feature branches are pushed by contributors
- The Lead Programmer reviews and merges changes into `develop`
- Do not assume direct push to `develop` is the normal path unless the user explicitly asks for it

---

## 11. Working Rules for Future Sessions

### Always remember

- Do not reintroduce florist/buket scope
- Do not assume public checkout is already fully connected
- Do not assume tracking is already real
- Do not force customer login
- Use MySQL assumptions for application behavior
- Favor incremental implementation: one flow at a time

### Preferred implementation order

When planning product work, prefer this order:

1. Clarify order/service flow
2. Connect create-order flow to the database
3. Stabilize admin review and pricing flow
4. Add tracking
5. Improve staged payments / gateway integration later

### Engineering guardrails

- Do not edit `vendor`, `node_modules`, build output, or generated cache unless explicitly needed
- Before broad multi-file changes, explain the plan briefly
- After edits, run tests/build/formatting when practical
- Follow existing Laravel + Filament structure unless there is a strong reason to refactor
