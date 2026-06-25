# AI Context: Memory & Architecture Guidelines

This file serves as a persistent context record for the AI assistant across sessions. It details the project goals, architecture, design system, key paths, external packages, and coding guardrails.

---

## 1. Project Goal & Current Focus
The application is a high-end Omani specialty coffee and premium gifting e-commerce platform.
- **Base Currency**: Omani Rial (OMR). Arabic localization displays OMR (or equivalent conversions as needed).
- **Core Features**:
  - **Digital Gift Cards**: Purchase flow integrated with Thawani API, redirecting users to checkout sessions, generating coupons upon successful payment with a 1-year expiry, and issuing them via automated emails.
  - **Custom Box Builder**: A step-by-step interactive builder allowing clients to select packaging designs (dynamic templates), box sizes (capacities), custom printed names (+2 days preparation time), custom gift messages, and fill the box with coffee crops and preparation tools.
  - **Admin Fulfillment & Templates Panel**: Dashboard panels to configure box templates (prices, colors, descriptions) and track box assembly status (`pending`, `in_printing`, `assembling`, `ready`).
  - **Experience Box Limits**: Customers are restricted to purchasing exactly **1 unit** from the `trial-boxes` category per order.

---

## 2. Architectural Decisions
- **Controllers**:
  - **Admin**: Placed in `app/Http/Controllers/Admin/`. Uses standard RESTful resource methods. Leverages Yajra DataTables (`Yajra\DataTables`) for server-side search, sorting, and pagination.
  - **Frontend**: Placed in `app/Http/Controllers/Frontend/` (e.g. `NewDesignController`, `CartController`, `CheckoutController`). Handles cart items, calculations, webhook validations, and client views.
- **Routing**:
  - Frontend routes are defined in `routes/web.php`.
  - Admin routes are defined in `routes/admin/admin.php` inside the admin route group with prefix `admin` and middleware `auth:admin`. All admin routes use prefix naming `admin.` (e.g., `admin.custom_box_orders.index`).
- **Blade Views**:
  - Admin templates are in `resources/views/admin/pages/` and extend `admin.master` with layout variables for sidebars (e.g., `@extends('admin.master', ['menu' => 'custom_box_orders'])`).
  - Frontend layouts render localized text based on the `$isRtl` variable.

---

## 3. UI & Figma Design Guidelines
- **Color Palette**:
  - Primary Dark Green: `#1A4231` (used for primary headers, buttons, active labels).
  - Background Neutral Beige: `#FAF9F5` or `#FAF9F5/70` (used for active cards, builder steps, input fields).
  - Borders: Light grey `#FAF9F5/40` or `border-gray-200` to maintain premium minimalism.
- **Typography & Layout**:
  - Uses rounded custom containers (`rounded-[28px]`, `rounded-2xl`).
  - Animations: Smooth transitions (`transition-all`, `group-hover:scale-105 transition-transform`).
  - No default generic browser styling. All selectors must look clean and modern.

---

## 4. Key Project Paths
- **Routes & Configs**:
  - `routes/web.php` (Frontend entrypoints)
  - `routes/admin/admin.php` (Admin CRUD & status management)
- **Controllers**:
  - `app/Http/Controllers/Frontend/NewDesignController.php` (Frontend customer endpoints)
  - `app/Http/Controllers/Frontend/CartController.php` (Cart actions, quantity validations)
  - `app/Http/Controllers/Frontend/CheckoutController.php` (Order placement, cart-to-custom-order syncing)
  - `app/Http/Controllers/ThawaniWebhookController.php` (Webhook payment callback notifications)
  - `app/Http/Controllers/Admin/CustomBoxTemplateController.php` (Template CRUD manager)
  - `app/Http/Controllers/Admin/CustomBoxOrderController.php` (Custom box fulfillment states)
  - `app/Http/Controllers/Admin/CsrInitiativeController.php` (CSR upload configurations)
- **Views**:
  - `resources/views/front/home/custom_box.blade.php` (Box builder page)
  - `resources/views/front/home/gift_cards.blade.php` (Gift vouchers store)
  - `resources/views/admin/pages/custom_box_templates/` (Template index, edit, create forms)
  - `resources/views/admin/pages/custom_box_orders/` (Fulfillment list)
  - `resources/views/admin/includes/leftsidebar.blade.php` (Sidebar layout navigation)
- **Localization**:
  - `resources/lang/ar.json` & `en.json` (General JSON translations)

---

## 5. Third-Party Packages & Dependencies
- **Thawani API**: Integrated via raw HTTP clients for creating sessions and retrieving checkout outcomes.
- **Yajra DataTables**: Drives tabular views in the administrative interface.
- **Hardcoded categories**: Slug `trial-boxes` is mapped to ID 3.

---

## 6. Guardrails & Coding Pitfalls to Avoid
- **Shell Commands**: The development OS is Windows PowerShell. Do not use shell chaining syntax like `&&` in command execution tools; chain with `;` instead.
- **Dynamic Pricing**: Always read base packaging prices and descriptions directly from database tables (`custom_box_templates`) rather than hardcoding numbers into JavaScript or controller files.
- **Trial Box Limits**: Enforce cart checks on both insertion (`add`) and update (`quantityUpdate`) endpoints. Show a clean message if the customer attempts to buy >1 unit.
- **Webhook Idempotency**: Prepend payment-related coupons with a standard reference (e.g. `GIFT-` for Gift Cards) to distinguish them and prevent order-matching 404 queries.

---

## 7. Client Feedback & Business Requirements Checklist
The following points outline the client's direct feedback regarding key business logic and operational goals:
- **E-Commerce Online Payments Launch (High Priority)**: Must be fully functional and stable within 7 to 10 days to capture the season's traffic. Direct online ordering via card/Apple Pay is the main sales channel.
- **Customized Box System**: Customers assemble custom contents dynamically (e.g. coffee crops + preparation cups/tools) instead of purchasing static boxes. Preparing custom orders with printed names takes **2 days** from the request date.
- **Trial / Experience Boxes**: Strictly limited to **one box per customer** at cost price. Visible on the site as samples to assist purchasing decisions.
- **Gifting Solutions (Gift Vouchers & Custom Boxes)**:
  - **Gift Cards**: Offered in fixed denominations (e.g. 15 or 20 OMR). The code is sent upon checkout to share with friends, allowing them to purchase products within that credit amount.
  - **Custom Box Templates**: Configured via the admin panel with selected template structures (3-5 designs).
- **CSR Section (Corporate Responsibility)**: Organized into dynamic blocks representing initiatives, utilizing PDF uploads in the admin dashboard to prevent text clutter, enabling simple one-click user downloads.
- **Special Offers & Packages**: Prominently featured with a dedicated icon in the frontend interface.
- **Subscriptions**: Enables recurring subscription options allowing customers to secure guaranteed cheaper prices for specific recurring monthly coffee delivery volumes.

