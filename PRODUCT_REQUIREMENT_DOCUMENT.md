# Product Requirement Document (PRD) — Zea Pet Shop

> **Version:** 1.0.0  
> **Last Updated:** 2025  
> **Project:** Zea Pet Shop — Online Pet Product Store  
> **Status:** Implemented & Deployed

---

## Table of Contents

1. [Product Overview](#1-product-overview)
2. [Goals & Vision](#2-goals--vision)
3. [Target Users](#3-target-users)
4. [User Roles & Permissions](#4-user-roles--permissions)
5. [Feature Requirements](#5-feature-requirements)
6. [User Stories](#6-user-stories)
7. [Page & Screen Inventory](#7-page--screen-inventory)
8. [Data Requirements](#8-data-requirements)
9. [Non-Functional Requirements](#9-non-functional-requirements)
10. [Constraints & Assumptions](#10-constraints--assumptions)
11. [Out of Scope](#11-out-of-scope)

---

## 1. Product Overview

**Zea Pet Shop** is a full-stack e-commerce web application for selling pet products online. Customers can browse products by category, view product details, add items to a cart, and complete purchases — either as guests or as registered users. A separate admin dashboard enables store management of products, categories, orders, and users.

**Live URLs:**
- Frontend: https://pet-shop-zea.vercel.app
- Backend API: https://pet-shop-zea-production.up.railway.app/api

---

## 2. Goals & Vision

### Business Goals

1. Provide an accessible and simple online storefront for purchasing pet supplies in Indonesia.
2. Allow both guest and registered customers to complete purchases with minimal friction.
3. Give store administrators full control over inventory, orders, and users through a dedicated admin panel.

### Product Vision

> A clean, fast, mobile-friendly pet shop where customers can discover and buy pet products easily, and where store owners can manage everything from one dashboard.

### Success Criteria

- Customers can browse and purchase products without requiring an account.
- Registered users can track their order history from an account page.
- Admins can manage the full product catalog, view all orders, update order statuses, and manage user accounts.
- The application is deployed and accessible online with no server setup required from the end user.

---

## 3. Target Users

| User Type | Description |
|---|---|
| **Guest Customer** | Visitor who browses and purchases without logging in. |
| **Registered Customer** | Logged-in user with access to profile management and order history. |
| **Store Administrator** | Staff member with full access to the admin dashboard for managing the store. |

---

## 4. User Roles & Permissions

### Permission Matrix

| Feature | Guest | Customer | Admin |
|---|---|---|---|
| Browse products | ✅ | ✅ | ✅ |
| View product detail | ✅ | ✅ | ✅ |
| Browse categories | ✅ | ✅ | ✅ |
| Add to cart | ✅ | ✅ | ✅ |
| Place order | ✅ | ✅ | ✅ |
| Track order by order number | ✅ | ✅ | ✅ |
| Register account | ✅ | — | — |
| Login | ✅ | ✅ | ✅ |
| View/edit profile | ❌ | ✅ | ✅ |
| View order history | ❌ | ✅ | ✅ |
| Access admin dashboard | ❌ | ❌ | ✅ |
| Manage products (CRUD) | ❌ | ❌ | ✅ |
| Manage categories (CRUD) | ❌ | ❌ | ✅ |
| View & update all orders | ❌ | ❌ | ✅ |
| Manage users | ❌ | ❌ | ✅ |

### Role Assignment

- Default role on registration: `customer`
- Admin role: manually assigned (via `role` column in `users` table or through admin user management)

---

## 5. Feature Requirements

### 5.1 Product Catalog

**FR-01 — Browse Products**
- Display a paginated list of all available products.
- Show product card with: image, name, price, original price (if discounted), rating, and badges (New, Promo).
- Support filtering by category, search by name, and sorting.

**FR-02 — Product Detail**
- Show full product information: name, description, price, ratings, stock status, category, and tags.
- Display product images (main + gallery).
- Allow the user to select quantity and add to cart.

**FR-03 — Category Browsing**
- Display all categories with icon and name.
- Allow filtering the product list by selecting a category.

---

### 5.2 Shopping Cart

**FR-04 — Cart Management**
- Users can add products to cart from the product listing or detail page.
- Cart state is persisted in the browser (localStorage).
- Users can update quantity or remove items in the cart.
- Cart displays subtotal per item and cart grand total.
- Cart is accessible to both guests and logged-in users.

---

### 5.3 Checkout & Orders

**FR-05 — Guest Checkout**
- A customer can complete an order without creating an account.
- Required fields: first name, last name, email, phone, address, city, province, postal code.
- Required selections: shipping method, payment method.
- Optional field: delivery notes.

**FR-06 — Shipping Methods**

| Code | Carrier |
|---|---|
| `jne` | JNE |
| `jnt` | J&T Express |
| `sicepat` | SiCepat |
| `gosend` | GoSend |

**FR-07 — Payment Methods**

| Code | Method |
|---|---|
| `transfer` | Bank Transfer |
| `gopay` | GoPay |
| `ovo` | OVO |
| `dana` | DANA |
| `cod` | Cash on Delivery |

**FR-08 — Order Confirmation**
- After placing an order, the system generates a unique `order_number`.
- The order number is displayed to the customer for tracking.

**FR-09 — Order Tracking**
- Any visitor can look up an order by its `order_number`.
- Displays order status, items, shipping info, and totals.

---

### 5.4 Authentication

**FR-10 — Customer Registration**
- Register with: name, email, password.
- On success, the user is immediately logged in.

**FR-11 — Customer Login**
- Login with: email, password.
- Returns a Bearer token stored in localStorage.
- Redirects guest-only routes for already-logged-in users.

**FR-12 — Logout**
- Revokes the Bearer token on the server.
- Clears local auth state from localStorage.

---

### 5.5 User Account

**FR-13 — Profile Management**
- Authenticated users can view and update: name, email, phone, address.
- Password change supported (requires current password or new password fields).

**FR-14 — Order History**
- Logged-in users can view a list of all their past orders.
- Each entry shows: order number, date, status, total, and items.

---

### 5.6 Admin Dashboard

**FR-15 — Dashboard Stats**
- Total revenue, total orders, total products, total users.
- Recent orders list.
- Order status breakdown.

**FR-16 — Product Management (CRUD)**
- Create product: name, category, price, original price, stock, description, image, tags, `is_new`, `is_promo`.
- Edit all product fields.
- Delete product (cascades to product_images; order_items retain snapshot).

**FR-17 — Category Management (CRUD)**
- Create category: name, slug, icon (emoji).
- Edit category fields.
- Delete category (cascades to products).

**FR-18 — Order Management**
- View all orders with pagination and filter by status.
- Update order status: `pending` → `paid` → `processing` → `shipped` → `delivered` / `cancelled`.

**FR-19 — User Management**
- View all registered users.
- Edit user details (name, email, role, phone, address).
- Delete user accounts.

---

## 6. User Stories

### Guest Customer

| ID | Story |
|---|---|
| US-01 | As a guest, I want to browse all pet products so I can find what I need. |
| US-02 | As a guest, I want to filter products by category so I can narrow down my options. |
| US-03 | As a guest, I want to search for products by name so I can find specific items quickly. |
| US-04 | As a guest, I want to view the details of a product including price, description, and images. |
| US-05 | As a guest, I want to add products to my cart and adjust quantities before purchasing. |
| US-06 | As a guest, I want to checkout without creating an account so the process is fast. |
| US-07 | As a guest, I want to select a shipping method and payment method during checkout. |
| US-08 | As a guest, I want to receive an order number after placing my order so I can track it later. |
| US-09 | As a guest, I want to track my order status using my order number. |
| US-10 | As a guest, I want to register an account so I can have a persistent profile and order history. |

### Registered Customer

| ID | Story |
|---|---|
| US-11 | As a registered user, I want to log in with my email and password to access my account. |
| US-12 | As a registered user, I want to view my profile and update my personal information. |
| US-13 | As a registered user, I want to change my password from my account page. |
| US-14 | As a registered user, I want to view all my past orders and their statuses. |
| US-15 | As a registered user, I want to be redirected away from the login/register pages automatically since I'm already logged in. |
| US-16 | As a registered user, I want to log out and have my session fully cleared. |

### Store Administrator

| ID | Story |
|---|---|
| US-17 | As an admin, I want to log into a dedicated admin panel to manage the store. |
| US-18 | As an admin, I want to see summary statistics (revenue, orders, products, users) on a dashboard. |
| US-19 | As an admin, I want to add new products with all relevant details and images. |
| US-20 | As an admin, I want to edit existing products to update pricing, stock, or descriptions. |
| US-21 | As an admin, I want to delete products that are no longer available. |
| US-22 | As an admin, I want to manage product categories (add, edit, delete). |
| US-23 | As an admin, I want to view all orders placed by customers, filterable by status. |
| US-24 | As an admin, I want to update the status of an order as it progresses. |
| US-25 | As an admin, I want to view and manage registered user accounts. |
| US-26 | As an admin, I want to edit a user's details or role. |
| US-27 | As an admin, I want to delete a user account. |

---

## 7. Page & Screen Inventory

### Customer-Facing Pages

| Page | Route | Auth Required | Description |
|---|---|---|---|
| Home | `/` | No | Landing page with hero, featured products, and categories |
| Products | `/products` | No | Full product catalog with search, filter, sort, pagination |
| Product Detail | `/products/:id` | No | Full product information, images, add-to-cart |
| Cart | `/cart` | No | Cart contents, quantity controls, total |
| Checkout | `/checkout` | No | Shipping & payment form, order placement |
| About | `/about` | No | About Zea Pet Shop information page |
| Contact | `/contact` | No | Contact information / form |
| Login | `/login` | Guest only | Login form (redirects away if already logged in) |
| Register | `/register` | Guest only | Registration form |
| Account | `/account` | Auth required | Profile edit + order history tabs |
| 404 | `/*` | No | Not found fallback |

### Admin Panel Pages

| Page | Route | Auth Required | Description |
|---|---|---|---|
| Admin Login | `/admin/login` | Admin-guest only | Admin-specific login form |
| Admin Dashboard | `/admin/dashboard` | Admin | Summary stats + recent orders |
| Admin Products | `/admin/products` | Admin | Products table with create/edit/delete |
| Admin Categories | `/admin/categories` | Admin | Categories table with create/edit/delete |
| Admin Orders | `/admin/orders` | Admin | All orders table with status filter + update |
| Admin Users | `/admin/users` | Admin | All users table with edit/delete |

---

## 8. Data Requirements

### Product Data

| Field | Required | Notes |
|---|---|---|
| Name | Yes | Product display name |
| Category | Yes | Must link to an existing category |
| Price | Yes | Current selling price in IDR (Rupiah) |
| Original Price | No | Pre-discount price; shown for comparison |
| Stock | Yes | Available inventory count |
| Description | No | HTML or plain text product description |
| Image | Yes | Primary product thumbnail |
| Tags | No | JSON array of tag strings |
| Is New | No | Display "New" badge |
| Is Promo | No | Display "Promo" badge |

### Order Data

All checkout fields are required:
- **Recipient:** first name, last name, email, phone
- **Address:** full address, city, province, postal code
- **Logistics:** shipping method, payment method
- **Optional:** delivery notes

Order items snapshot fields (`product_name`, `product_image`, `price`) are stored at time of purchase to preserve historical accuracy.

### User Data

| Field | Required at Registration | Notes |
|---|---|---|
| Name | Yes | |
| Email | Yes | Unique, used as login |
| Password | Yes | Hashed with bcrypt |
| Phone | No | Editable from profile |
| Address | No | Editable from profile |
| Role | Auto | Defaults to `customer` |

---

## 9. Non-Functional Requirements

### Performance

- **NFR-01:** Product list page should load within 3 seconds on a standard broadband connection.
- **NFR-02:** API responses for product listing should be paginated to prevent large payloads.
- **NFR-03:** Frontend assets are served via Vercel's global CDN for fast load times worldwide.

### Security

- **NFR-04:** Passwords are stored as bcrypt hashes; plaintext passwords are never stored.
- **NFR-05:** All API endpoints that modify data require authentication via Bearer token.
- **NFR-06:** Admin endpoints require the `admin` role in addition to authentication.
- **NFR-07:** API uses HTTPS in production; HTTP is not permitted.
- **NFR-08:** Database SSL is enforced (`DB_SSLMODE=require`) for all NeonDB connections.
- **NFR-09:** CORS is configured; credentials (cookies/sessions) are disabled for cross-origin requests.
- **NFR-10:** `order_items` snapshots prevent product tampering from affecting historical order records.

### Reliability

- **NFR-11:** The application is hosted on managed cloud platforms (Vercel, Railway, NeonDB) with built-in uptime monitoring.
- **NFR-12:** Guest checkout ensures orders can be placed even during authentication service disruption.

### Usability

- **NFR-13:** The UI must be responsive and usable on mobile devices (320px and above).
- **NFR-14:** All page titles update dynamically using Vue Router meta to reflect the current page.
- **NFR-15:** Smooth scroll-to-top behavior on route navigation.
- **NFR-16:** The admin panel is accessible via a dedicated layout with sidebar navigation.

### Maintainability

- **NFR-17:** Backend and frontend are fully decoupled; each can be deployed and updated independently.
- **NFR-18:** Database schema changes are managed through Laravel migrations for version control.
- **NFR-19:** All API calls are centralized in a single `services/api.js` file for easy maintenance.

---

## 10. Constraints & Assumptions

### Constraints

- **Language:** Product names, descriptions, and UI labels are primarily in **Bahasa Indonesia**.
- **Currency:** All prices are in **Indonesian Rupiah (IDR)**, stored as unsigned integers (no decimal).
- **Shipping:** Shipping options are limited to Indonesian carriers (JNE, J&T, SiCepat, GoSend).
- **Payment:** Payment options are limited to Indonesian platforms (GoPay, OVO, DANA, Transfer, COD).
- **Images:** Product images are stored as URL strings; no file upload system is implemented.
- **Email:** No transactional email (order confirmation, password reset) is implemented.
- **Ratings:** Product ratings and review counts are stored but not modifiable through customer-facing UI.

### Assumptions

- The store operates in Indonesia (locale, currency, shipping, payment).
- A single admin account is sufficient for initial operation.
- Order payment verification is handled manually (no payment gateway integration).
- Product stock is managed manually by admin; no automatic stock deduction on order placement (unless implemented in `OrderController`).
- The system does not require multi-language (i18n) support at this stage.

---

## 11. Out of Scope

The following features are explicitly **not** part of this version:

| Feature | Reason |
|---|---|
| Payment gateway integration (Midtrans, Stripe, etc.) | Manual payment confirmation assumed |
| Email notifications (order confirmation, shipping updates) | Not implemented |
| Product reviews and ratings by customers | Rating data stored but not collected via UI |
| Wishlist / saved products | Not planned in v1 |
| Product stock auto-decrement on order | Not confirmed in current implementation |
| Multi-vendor / seller support | Single-store model only |
| Multi-language (i18n) | Indonesian-only UI |
| Mobile native app (iOS/Android) | Web-only |
| Real-time order updates (WebSocket) | Not implemented |
| Advanced analytics / reporting | Basic admin stats only |
| Discount codes / vouchers | Not planned in v1 |
| Product variants (size, color) | Single variant per product |
| Return / refund management | Not planned in v1 |
