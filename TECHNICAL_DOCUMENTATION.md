# Technical Documentation — Zea Pet Shop

> **Version:** 1.0.0  
> **Last Updated:** 2025  
> **Project:** Zea Pet Shop — Full-Stack E-Commerce Application

---

## Table of Contents

1. [System Architecture](#1-system-architecture)
2. [Technology Stack](#2-technology-stack)
3. [Project Structure](#3-project-structure)
4. [Database Schema](#4-database-schema)
5. [API Reference](#5-api-reference)
6. [Authentication Flow](#6-authentication-flow)
7. [Frontend Architecture](#7-frontend-architecture)
8. [Backend Architecture](#8-backend-architecture)
9. [Deployment & Infrastructure](#9-deployment--infrastructure)
10. [Environment Variables](#10-environment-variables)
11. [Local Development Setup](#11-local-development-setup)

---

## 1. System Architecture

Zea Pet Shop follows a **decoupled architecture** pattern with a separate frontend (SPA) and backend (REST API).

```
┌─────────────────────────────────────────────────┐
│                   CLIENT LAYER                  │
│                                                 │
│   Browser ──► Vue 3 SPA (Vercel)               │
│               https://pet-shop-zea.vercel.app   │
└────────────────────────┬────────────────────────┘
                         │ HTTPS / JSON
                         │ Bearer Token
┌────────────────────────▼────────────────────────┐
│                    API LAYER                    │
│                                                 │
│   Laravel 11 REST API (Railway)                │
│   https://pet-shop-zea-production.up.railway.app│
└────────────────────────┬────────────────────────┘
                         │ TCP / SSL
┌────────────────────────▼────────────────────────┐
│                  DATABASE LAYER                 │
│                                                 │
│   PostgreSQL 17 (NeonDB — AWS ap-southeast-1)   │
│   ep-jolly-boat-a1fk7act.ap-southeast-1         │
└─────────────────────────────────────────────────┘
```

**Key architectural decisions:**

- **Stateless API** — Laravel Sanctum configured for Bearer Token (not cookie-based) to support cross-origin SPA.
- **SPA Routing** — Vercel rewrites all paths to `index.html` so Vue Router handles client-side navigation.
- **Guest Checkout** — Orders can be placed without authentication; `user_id` is nullable on the `orders` table.
- **No CORS Credentials** — `supports_credentials: false` and `allowed_origins: ['*']` for straightforward cross-origin access.

---

## 2. Technology Stack

### Frontend

| Technology | Version | Purpose |
|---|---|---|
| Vue 3 | ^3.5.25 | UI framework (Composition API) |
| Vite | ^7.3.1 | Build tool & dev server |
| Pinia | ^3.0.4 | State management |
| Vue Router | ^4.6.4 | Client-side routing |
| Axios | ^1.13.6 | HTTP client |
| Tailwind CSS | ^3.4.19 | Utility-first CSS framework |
| VueUse | ^14.2.1 | Vue composition utilities |
| PostCSS | ^8.5.8 | CSS post-processing |
| Autoprefixer | ^10.4.27 | CSS vendor prefixing |

### Backend

| Technology | Version | Purpose |
|---|---|---|
| PHP | ^8.4 | Runtime |
| Laravel | ^11.0 | Web framework |
| Laravel Sanctum | ^4.0 | API authentication (Bearer tokens) |
| Laravel Tinker | ^2.9 | Interactive REPL |
| PHPUnit | ^11.0.1 | Testing framework |

### Database

| Technology | Version | Purpose |
|---|---|---|
| PostgreSQL | 17 | Primary relational database |
| NeonDB | — | Serverless PostgreSQL hosting |

### Infrastructure

| Service | Provider | Purpose |
|---|---|---|
| Frontend Hosting | Vercel | Vue SPA (CDN + edge) |
| Backend Hosting | Railway | Laravel + PHP 8.4 |
| Database Hosting | NeonDB (AWS ap-southeast-1) | PostgreSQL serverless |

---

## 3. Project Structure

### Repository Layout

```
c:\zea-pet-shop\
├── index.html                   # Vite entry HTML
├── package.json                 # Frontend dependencies
├── vite.config.js               # Vite configuration
├── tailwind.config.js           # Tailwind configuration
├── postcss.config.js            # PostCSS configuration
├── vercel.json                  # Vercel SPA rewrite rules
├── .env                         # Local frontend env vars
├── .env.production              # Production frontend env vars
├── public/                      # Static assets
├── src/                         # Frontend source
│   ├── main.js                  # Vue app entry point
│   ├── App.vue                  # Root component
│   ├── style.css                # Global styles
│   ├── assets/                  # Images, icons, etc.
│   ├── components/              # Shared components
│   │   ├── TheNavbar.vue
│   │   ├── TheFooter.vue
│   │   ├── ProductCard.vue
│   │   └── HelloWorld.vue
│   ├── layouts/
│   │   └── AdminLayout.vue      # Admin panel wrapper layout
│   ├── pages/                   # Route-level page components
│   │   ├── HomePage.vue
│   │   ├── ProductsPage.vue
│   │   ├── ProductDetailPage.vue
│   │   ├── CartPage.vue
│   │   ├── CheckoutPage.vue
│   │   ├── AccountPage.vue
│   │   ├── LoginPage.vue
│   │   ├── RegisterPage.vue
│   │   ├── AboutPage.vue
│   │   ├── ContactPage.vue
│   │   ├── NotFoundPage.vue
│   │   └── admin/
│   │       ├── AdminLoginPage.vue
│   │       ├── AdminDashboardPage.vue
│   │       ├── AdminProductsPage.vue
│   │       ├── AdminCategoriesPage.vue
│   │       ├── AdminOrdersPage.vue
│   │       └── AdminUsersPage.vue
│   ├── router/
│   │   └── index.js             # Vue Router configuration + guards
│   ├── stores/
│   │   ├── auth.js              # Auth state (Pinia)
│   │   ├── cart.js              # Cart state (Pinia)
│   │   └── products.js          # Products state (Pinia)
│   └── services/
│       └── api.js               # Axios instance + all API calls
└── backend/                     # Laravel backend
    ├── .env                     # Backend environment config
    ├── .php-version             # PHP version pin (8.4)
    ├── nixpacks.toml            # Railway build config
    ├── Procfile                 # Railway start command
    ├── composer.json            # PHP dependencies
    ├── app/
    │   ├── Http/
    │   │   ├── Controllers/
    │   │   │   └── Api/
    │   │   │       ├── AdminController.php
    │   │   │       ├── AuthController.php
    │   │   │       ├── CategoryController.php
    │   │   │       ├── OrderController.php
    │   │   │       ├── ProductController.php
    │   │   │       ├── ProfileController.php
    │   │   │       └── UserController.php
    │   │   └── Middleware/
    │   └── Models/
    ├── config/
    │   └── cors.php             # CORS configuration
    ├── database/
    │   ├── migrations/          # 7 migration files
    │   └── seeders/
    │       ├── DatabaseSeeder.php
    │       ├── CategorySeeder.php
    │       └── ProductSeeder.php
    └── routes/
        └── api.php              # All API route definitions
```

---

## 4. Database Schema

### Entity Relationship Overview

```
users ──────────────────── orders (user_id nullable)
                               │
categories ─── products ───────┤ order_items
                   │               └── products (product_id nullable)
                   └── product_images
```

### Tables

#### `users`

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| name | varchar | NOT NULL | Display name |
| email | varchar | NOT NULL, UNIQUE | Login identifier |
| email_verified_at | timestamp | nullable | Email verification |
| password | varchar | NOT NULL | Bcrypt hashed |
| phone | varchar | nullable | Contact number |
| address | text | nullable | Shipping address |
| role | varchar | DEFAULT 'customer' | `admin` or `customer` |
| remember_token | varchar | nullable | Session token |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### `categories`

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| name | varchar | NOT NULL | Category name |
| slug | varchar | NOT NULL, UNIQUE | URL-safe identifier |
| icon | varchar(10) | nullable | Emoji icon |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### `products`

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| category_id | bigint | FK → categories, CASCADE | Parent category |
| name | varchar | NOT NULL | Product name |
| slug | varchar | NOT NULL, UNIQUE | URL-safe identifier |
| price | unsigned int | NOT NULL | Current price (IDR) |
| original_price | unsigned int | nullable | Pre-discount price (IDR) |
| rating | decimal(3,1) | DEFAULT 0 | Average star rating |
| reviews_count | unsigned int | DEFAULT 0 | Number of reviews |
| stock | unsigned int | DEFAULT 0 | Available quantity |
| sold_count | unsigned int | DEFAULT 0 | Total units sold |
| image | varchar | NOT NULL | Main thumbnail path/URL |
| description | text | nullable | Product description |
| tags | jsonb | DEFAULT '[]' | Tag array (JSON) |
| is_new | boolean | DEFAULT false | New product badge |
| is_promo | boolean | DEFAULT false | Promo badge |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### `product_images`

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| product_id | bigint | FK → products, CASCADE | Parent product |
| image_path | varchar | NOT NULL | Image path/URL |

#### `orders`

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| user_id | bigint | FK → users nullable, NULL ON DELETE | Null if guest checkout |
| order_number | varchar | NOT NULL, UNIQUE | Human-readable order ID |
| status | varchar | DEFAULT 'pending' | `pending` / `paid` / `processing` / `shipped` / `delivered` / `cancelled` |
| first_name | varchar | NOT NULL | Recipient first name |
| last_name | varchar | NOT NULL | Recipient last name |
| email | varchar | NOT NULL | Contact email |
| phone | varchar(20) | NOT NULL | Contact phone |
| address | text | NOT NULL | Street address |
| city | varchar | NOT NULL | City |
| province | varchar | NOT NULL | Province |
| postal_code | varchar(10) | NOT NULL | Postal code |
| shipping_method | varchar | NOT NULL | `jne` / `jnt` / `sicepat` / `gosend` |
| payment_method | varchar | NOT NULL | `transfer` / `gopay` / `ovo` / `dana` / `cod` |
| notes | text | nullable | Delivery notes |
| subtotal | unsigned int | NOT NULL | Products total (IDR) |
| shipping_cost | unsigned int | NOT NULL | Shipping cost (IDR) |
| total | unsigned int | NOT NULL | Grand total (IDR) |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### `order_items`

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| order_id | bigint | FK → orders, CASCADE | Parent order |
| product_id | bigint | FK → products nullable, NULL ON DELETE | May be null if product deleted |
| product_name | varchar | NOT NULL | Snapshot of product name |
| product_image | varchar | nullable | Snapshot of product image |
| price | unsigned int | NOT NULL | Unit price at time of order |
| qty | unsigned smallint | NOT NULL | Quantity |
| total | unsigned int | NOT NULL | `price × qty` |

> **Note:** `order_items` stores a snapshot of `product_name` and `product_image` so historical orders remain accurate even if the product is later updated or deleted.

#### `personal_access_tokens` (Sanctum)

Managed by Laravel Sanctum. Stores Bearer tokens linked to users.

#### `cache` & `sessions`

Standard Laravel cache and session tables.

---

## 5. API Reference

**Base URL (Production):** `https://pet-shop-zea-production.up.railway.app/api`

All requests must include the header:
```
Accept: application/json
Content-Type: application/json
```

Protected endpoints additionally require:
```
Authorization: Bearer <token>
```

---

### Public Endpoints

#### Products

| Method | Endpoint | Description |
|---|---|---|
| GET | `/products` | List products (paginated + filterable) |
| GET | `/products/{id}` | Get single product detail |

**GET `/products` Query Parameters:**

| Parameter | Type | Description |
|---|---|---|
| page | int | Page number |
| per_page | int | Items per page |
| category | string | Filter by category slug |
| search | string | Search by name |
| sort | string | Sort order |
| is_new | bool | Filter new products |
| is_promo | bool | Filter promo products |

---

#### Categories

| Method | Endpoint | Description |
|---|---|---|
| GET | `/categories` | List all categories |
| GET | `/categories/{slug}/products` | Get products by category slug |

---

#### Orders (Guest Checkout & Tracking)

| Method | Endpoint | Description |
|---|---|---|
| POST | `/orders` | Place a new order (guest or authenticated) |
| GET | `/orders/{orderNumber}` | Track order by order number |

**POST `/orders` Request Body:**

```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "081234567890",
  "address": "Jl. Contoh No. 1",
  "city": "Jakarta",
  "province": "DKI Jakarta",
  "postal_code": "10110",
  "shipping_method": "jne",
  "payment_method": "transfer",
  "notes": "Titip ke satpam",
  "items": [
    { "product_id": 1, "qty": 2 }
  ]
}
```

---

### Authentication Endpoints

| Method | Endpoint | Auth Required | Description |
|---|---|---|---|
| POST | `/auth/register` | No | Register new customer account |
| POST | `/auth/login` | No | Login, returns Bearer token |
| POST | `/auth/logout` | Yes | Revoke current Bearer token |
| GET | `/auth/user` | Yes | Get current authenticated user |

**POST `/auth/login` Response:**

```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "customer"
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxxxx"
}
```

---

### Profile Endpoints (Authenticated)

| Method | Endpoint | Description |
|---|---|---|
| GET | `/profile` | Get current user's profile |
| PUT | `/profile` | Update name, email, phone, address, password |
| GET | `/profile/orders` | Get current user's order history |

---

### Admin Endpoints (Authenticated + Admin Role)

#### Dashboard

| Method | Endpoint | Description |
|---|---|---|
| GET | `/admin/stats` | Get dashboard stats (totals, recent orders, etc.) |

#### Product Management

| Method | Endpoint | Description |
|---|---|---|
| POST | `/admin/products` | Create new product |
| PUT | `/admin/products/{id}` | Update product |
| DELETE | `/admin/products/{id}` | Delete product |

#### Category Management

| Method | Endpoint | Description |
|---|---|---|
| POST | `/admin/categories` | Create new category |
| PUT | `/admin/categories/{id}` | Update category |
| DELETE | `/admin/categories/{id}` | Delete category |

#### Order Management

| Method | Endpoint | Description |
|---|---|---|
| GET | `/admin/orders` | List all orders (paginated + filterable by status) |
| PUT | `/admin/orders/{id}/status` | Update order status |

#### User Management

| Method | Endpoint | Description |
|---|---|---|
| GET | `/admin/users` | List all users |
| GET | `/admin/users/{id}` | Get user detail |
| PUT | `/admin/users/{id}` | Update user |
| DELETE | `/admin/users/{id}` | Delete user |

---

## 6. Authentication Flow

Zea Pet Shop uses **Laravel Sanctum with Bearer Token** authentication (no cookies/sessions).

### Login Flow

```
Client                             Server
  │                                  │
  │── POST /auth/login ─────────────►│
  │   { email, password }            │ Validates credentials
  │                                  │ Creates personal_access_token
  │◄─ { user, token } ──────────────│
  │                                  │
  │  Stores in localStorage:         │
  │  - auth_user (JSON)              │
  │  - auth_token (string)           │
  │                                  │
  │── GET /auth/user ───────────────►│
  │   Authorization: Bearer <token>  │ Validates token
  │◄─ { user data } ────────────────│
```

### Authenticated Request Flow

```javascript
// src/services/api.js — axios interceptor
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})
```

### Route Guards (Vue Router)

```
Route meta flags:
  requiresAuth  — redirect to /login if not authenticated
  requiresAdmin — redirect to /admin/login if not admin
  guest         — redirect to / if already authenticated
  guestAdmin    — redirect to /admin/dashboard if already admin
```

### Token Storage

| Key | Storage | Value |
|---|---|---|
| `auth_token` | localStorage | Sanctum Bearer token string |
| `auth_user` | localStorage | JSON-serialized user object |

> **Security note:** Tokens are stored in localStorage and sent as Bearer headers. CSRF protection is not needed since cookies are not used.

---

## 7. Frontend Architecture

### State Management (Pinia Stores)

#### `stores/auth.js`

Manages authentication state.

| State | Type | Description |
|---|---|---|
| `user` | ref | Current user object (from localStorage) |
| `token` | ref | Bearer token string (from localStorage) |
| `isLoggedIn` | computed | True if token exists |
| `isAdmin` | computed | True if `user.role === 'admin'` |

| Action | Description |
|---|---|
| `doLogin(email, password)` | Calls API, persists user + token |
| `doRegister(data)` | Calls API, persists user + token |
| `doLogout()` | Calls API logout, clears localStorage |
| `fetchUser()` | Refreshes user data from API |
| `updateUser(userData)` | Updates user in state + localStorage |

#### `stores/cart.js`

Manages shopping cart state (localStorage-persisted).

#### `stores/products.js`

Manages products list, filters, and pagination state.

---

### Service Layer (`services/api.js`)

Single Axios instance with:
- `baseURL` from `VITE_API_URL` env var (falls back to production URL)
- `Content-Type: application/json` and `Accept: application/json` headers
- Request interceptor that auto-attaches `Authorization: Bearer <token>`

**Exported functions:**

| Function | Method | Endpoint |
|---|---|---|
| `fetchProducts(params)` | GET | `/products` |
| `fetchProduct(id)` | GET | `/products/{id}` |
| `fetchCategories()` | GET | `/categories` |
| `createOrder(payload)` | POST | `/orders` |
| `fetchOrder(orderNumber)` | GET | `/orders/{orderNumber}` |
| `login(credentials)` | POST | `/auth/login` |
| `register(data)` | POST | `/auth/register` |
| `logout()` | POST | `/auth/logout` |
| `fetchAuthUser()` | GET | `/auth/user` |
| `updateProfile(data)` | PUT | `/profile` |
| `fetchMyOrders(params)` | GET | `/profile/orders` |
| `fetchAdminStats()` | GET | `/admin/stats` |
| `adminCreateProduct(data)` | POST | `/admin/products` |
| `adminUpdateProduct(id, data)` | PUT | `/admin/products/{id}` |
| `adminDeleteProduct(id)` | DELETE | `/admin/products/{id}` |
| `adminCreateCategory(data)` | POST | `/admin/categories` |
| `adminUpdateCategory(id, data)` | PUT | `/admin/categories/{id}` |
| `adminDeleteCategory(id)` | DELETE | `/admin/categories/{id}` |
| `fetchAdminOrders(params)` | GET | `/admin/orders` |
| `adminUpdateOrderStatus(id, status)` | PUT | `/admin/orders/{id}/status` |
| `fetchAdminUsers(params)` | GET | `/admin/users` |
| `fetchAdminUser(id)` | GET | `/admin/users/{id}` |
| `adminUpdateUser(id, data)` | PUT | `/admin/users/{id}` |
| `adminDeleteUser(id)` | DELETE | `/admin/users/{id}` |

---

### Routing

All routes defined in `src/router/index.js` using `createWebHistory()` (HTML5 History API).

| Route | Component | Auth | Description |
|---|---|---|---|
| `/` | HomePage | — | Landing page |
| `/products` | ProductsPage | — | Product catalog |
| `/products/:id` | ProductDetailPage | — | Product detail |
| `/cart` | CartPage | — | Shopping cart |
| `/checkout` | CheckoutPage | — | Order checkout |
| `/about` | AboutPage | — | About us |
| `/contact` | ContactPage | — | Contact page |
| `/account` | AccountPage | `requiresAuth` | User profile + order history |
| `/login` | LoginPage | `guest` | Login form |
| `/register` | RegisterPage | `guest` | Registration form |
| `/admin/login` | AdminLoginPage | `guestAdmin` | Admin login |
| `/admin/dashboard` | AdminDashboardPage | `requiresAdmin` | Admin dashboard |
| `/admin/products` | AdminProductsPage | `requiresAdmin` | Products CRUD |
| `/admin/categories` | AdminCategoriesPage | `requiresAdmin` | Categories CRUD |
| `/admin/orders` | AdminOrdersPage | `requiresAdmin` | Orders management |
| `/admin/users` | AdminUsersPage | `requiresAdmin` | Users management |
| `/:pathMatch(.*)* ` | NotFoundPage | — | 404 fallback |

---

## 8. Backend Architecture

### Laravel Application Structure

```
backend/app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AdminController.php    — Dashboard stats
│   │       ├── AuthController.php     — register, login, logout, user
│   │       ├── CategoryController.php — index, products, store, update, destroy
│   │       ├── OrderController.php    — store (guest), show (tracking), index, updateStatus
│   │       ├── ProductController.php  — index, show, store, update, destroy
│   │       ├── ProfileController.php  — show, update, orders
│   │       └── UserController.php     — index, show, update, destroy
│   └── Middleware/
└── Models/
    ├── User.php
    ├── Category.php
    ├── Product.php
    ├── ProductImage.php
    ├── Order.php
    └── OrderItem.php
```

### CORS Configuration (`config/cors.php`)

```php
'allowed_origins'         => ['*'],
'allowed_methods'         => ['*'],
'allowed_headers'         => ['*'],
'supports_credentials'    => false,
```

### Sanctum Configuration

- Driver: `token` (not `session`)
- Tokens stored in `personal_access_tokens` table
- No cross-origin cookie/session support required

---

## 9. Deployment & Infrastructure

### Railway (Backend)

**Build configuration (`nixpacks.toml`):**

```toml
[phases.setup]
nixPkgs = ["php84", "php84Packages.composer"]

[phases.build]
cmds = [
  "composer install --no-dev --optimize-autoloader",
  "php artisan config:clear",
  "php artisan config:cache",
  "php artisan route:cache",
  "php artisan view:cache"
]

[start]
cmd = "php artisan serve --host=0.0.0.0 --port=$PORT"
```

**PHP version pin (`.php-version`):**

```
8.4
```

**Railway environment variables required:**

```
APP_KEY=<generated>
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql
DB_HOST=ep-jolly-boat-a1fk7act.ap-southeast-1.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=<secret>
DB_SSLMODE=require
CACHE_DRIVER=database
SESSION_DRIVER=database
NIXPACKS_PHP_VERSION=8.4
```

---

### Vercel (Frontend)

**SPA routing (`vercel.json`):**

```json
{
  "rewrites": [
    { "source": "/(.*)", "destination": "/index.html" }
  ]
}
```

**Vercel environment variable:**

```
VITE_API_URL=https://pet-shop-zea-production.up.railway.app/api
```

**Build settings:**
- Framework: Vite
- Build command: `npm run build`
- Output directory: `dist`

---

### NeonDB (Database)

- Provider: Neon (serverless PostgreSQL)
- Cloud: AWS ap-southeast-1 (Singapore)
- Connection mode: SSL required (`DB_SSLMODE=require`)
- Database: `neondb`
- User: `neondb_owner`

---

### Initial Database Setup

```bash
# Run migrations
php artisan migrate

# Migrate Sanctum tokens table (if not included in main migration run)
php artisan migrate --path=vendor/laravel/sanctum/database/migrations

# Seed categories and products
php artisan db:seed
```

---

## 10. Environment Variables

### Frontend (`.env` / `.env.production`)

| Variable | Description | Example |
|---|---|---|
| `VITE_API_URL` | Backend API base URL | `https://pet-shop-zea-production.up.railway.app/api` |

### Backend (`.env`)

| Variable | Description | Example |
|---|---|---|
| `APP_NAME` | Application name | `Zea Pet Shop` |
| `APP_ENV` | Environment | `production` |
| `APP_KEY` | Laravel encryption key | `base64:xxx...` |
| `APP_DEBUG` | Debug mode | `false` |
| `APP_URL` | Backend URL | `https://pet-shop-zea-production.up.railway.app` |
| `DB_CONNECTION` | Database driver | `pgsql` |
| `DB_HOST` | Database host | `ep-jolly-boat-a1fk7act...neon.tech` |
| `DB_PORT` | Database port | `5432` |
| `DB_DATABASE` | Database name | `neondb` |
| `DB_USERNAME` | Database user | `neondb_owner` |
| `DB_PASSWORD` | Database password | `<secret>` |
| `DB_SSLMODE` | SSL requirement | `require` |
| `CACHE_DRIVER` | Cache backend | `database` |
| `SESSION_DRIVER` | Session backend | `database` |
| `SANCTUM_STATEFUL_DOMAINS` | Allowed SPA domains | `pet-shop-zea.vercel.app` |

---

## 11. Local Development Setup

### Prerequisites

- Node.js >= 18
- PHP >= 8.4
- Composer >= 2
- PostgreSQL (local) or NeonDB credentials

---

### Frontend (Vue 3)

```bash
# 1. Navigate to project root
cd c:\zea-pet-shop

# 2. Install dependencies
npm install

# 3. Configure environment
# Create .env with:
# VITE_API_URL=http://localhost:8000/api

# 4. Start dev server
npm run dev
# → http://localhost:5173
```

---

### Backend (Laravel)

```bash
# 1. Navigate to backend
cd c:\zea-pet-shop\backend

# 2. Install PHP dependencies
composer install

# 3. Configure environment
# Copy .env.example to .env and fill in:
# DB_CONNECTION=pgsql
# DB_HOST=localhost (or NeonDB host)
# DB_DATABASE=zea_pet_shop
# DB_USERNAME=postgres
# DB_PASSWORD=<your password>

# 4. Generate app key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Seed database
php artisan db:seed

# 7. Start development server
php artisan serve
# → http://localhost:8000
```

---

### Default Admin Credentials

```
Email:    admin@zeapetshop.com
Password: password123
```

---

### Production URLs

| Service | URL |
|---|---|
| Frontend | https://pet-shop-zea.vercel.app |
| Backend API | https://pet-shop-zea-production.up.railway.app/api |
