# Architecture - Inventory & Sales Admin Panel API

## Tech Stack
- PHP 8.4 with `declare(strict_types=1)`
- Laravel 12
- PostgreSQL (Docker)
- Laravel Sanctum (API Authentication)

---

## 1. FOLDER STRUCTURE

```
app/
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── ProductImage.php
│   ├── StockMovement.php
│   ├── SalesOrder.php
│   ├── SalesOrderItem.php
│   └── User.php
│
├── Domain/
│   ├── API/
│   │   ├── Auth/
│   │   │   ├── Repositories/
│   │   │   │   └── AuthRepository.php
│   │   │   └── Services/
│   │   │       └── AuthService.php
│   │   │
│   │   └── Profile/
│   │       └── Services/
│   │           └── ProfileService.php
│   │
│   ├── Backoffice/
│   │   ├── Category/
│   │   │   ├── Repositories/
│   │   │   │   └── CategoryRepository.php
│   │   │   ├── Services/
│   │   │   │   └── CategoryService.php
│   │   │   └── Observers/
│   │   │       └── CategoryObserver.php
│   │   │
│   │   ├── Product/
│   │   │   ├── Repositories/
│   │   │   │   └── ProductRepository.php
│   │   │   ├── Services/
│   │   │   │   └── ProductService.php
│   │   │   ├── Observers/
│   │   │   │   └── ProductObserver.php
│   │   │   └── Enums/
│   │   │       └── ProductStatusType.php
│   │   │
│   │   ├── Inventory/
│   │   │   ├── Repositories/
│   │   │   │   └── StockMovementRepository.php
│   │   │   ├── Services/
│   │   │   │   └── StockMovementService.php
│   │   │   ├── Observers/
│   │   │   │   └── StockMovementObserver.php
│   │   │   └── Enums/
│   │   │       └── StockMovementType.php
│   │   │
│   │   ├── Sales/
│   │   │   ├── Repositories/
│   │   │   │   ├── SalesOrderRepository.php
│   │   │   │   └── SalesOrderItemRepository.php
│   │   │   ├── Services/
│   │   │   │   └── SalesOrderService.php
│   │   │   ├── Observers/
│   │   │   │   └── SalesOrderObserver.php
│   │   │   └── Enums/
│   │   │       └── OrderStatusType.php
│   │   │
│   │   ├── User/
│   │   │   ├── Repositories/
│   │   │   │   └── UserRepository.php
│   │   │   ├── Services/
│   │   │   │   └── UserService.php
│   │   │   ├── Observers/
│   │   │   │   └── UserObserver.php
│   │   │   └── Enums/
│   │   │       ├── PermissionType.php
│   │   │       └── RoleType.php
│   │   │
│   │   └── Dashboard/
│   │       └── Services/
│   │           └── DashboardService.php
│   │
│   └── Shared/
│       ├── Repositories/
│       │   └── BaseRepository.php
│       └── Enums/
│           └── StatusType.php
│
├── Presentation/
│   ├── API/
│   │   └── V1/
│   │       ├── Auth/
│   │       │   ├── Controllers/
│   │       │   │   └── AuthController.php
│   │       │   ├── Requests/
│   │       │   │   ├── AuthLoginRequest.php
│   │       │   │   └── AuthRegisterRequest.php
│   │       │   └── Responses/
│   │       │       ├── AuthLoginResponse.php
│   │       │       ├── AuthRegisterResponse.php
│   │       │       └── AuthMeResponse.php
│   │       │
│   │       └── Profile/
│   │           ├── Controllers/
│   │           │   └── ProfileController.php
│   │           ├── Requests/
│   │           │   └── ProfileUpdateRequest.php
│   │           └── Responses/
│   │               ├── ProfileShowResponse.php
│   │               └── ProfileUpdateResponse.php
│   │
│   └── Backoffice/
│       └── V1/
│           ├── Category/
│           │   ├── Controllers/
│           │   │   └── CategoryController.php
│           │   ├── Requests/
│           │   │   ├── CategoryQueryRequest.php
│           │   │   ├── CategoryCreateRequest.php
│           │   │   └── CategoryUpdateRequest.php
│           │   └── Responses/
│           │       ├── CategoryQueryResponse.php
│           │       ├── CategoryCreateResponse.php
│           │       ├── CategoryShowResponse.php
│           │       └── CategoryUpdateResponse.php
│           │
│           ├── Product/
│           │   ├── Controllers/
│           │   │   └── ProductController.php
│           │   ├── Requests/
│           │   │   ├── ProductQueryRequest.php
│           │   │   ├── ProductCreateRequest.php
│           │   │   └── ProductUpdateRequest.php
│           │   └── Responses/
│           │       ├── ProductQueryResponse.php
│           │       ├── ProductCreateResponse.php
│           │       ├── ProductShowResponse.php
│           │       └── ProductUpdateResponse.php
│           │
│           ├── Inventory/
│           │   ├── Controllers/
│           │   │   └── StockMovementController.php
│           │   ├── Requests/
│           │   │   ├── StockMovementQueryRequest.php
│           │   │   └── StockMovementCreateRequest.php
│           │   └── Responses/
│           │       ├── StockMovementQueryResponse.php
│           │       ├── StockMovementCreateResponse.php
│           │       └── StockMovementShowResponse.php
│           │
│           ├── Sales/
│           │   ├── Controllers/
│           │   │   └── SalesOrderController.php
│           │   ├── Requests/
│           │   │   ├── SalesOrderQueryRequest.php
│           │   │   ├── SalesOrderCreateRequest.php
│           │   │   └── SalesOrderUpdateRequest.php
│           │   └── Responses/
│           │       ├── SalesOrderQueryResponse.php
│           │       ├── SalesOrderCreateResponse.php
│           │       ├── SalesOrderShowResponse.php
│           │       └── SalesOrderUpdateResponse.php
│           │
│           ├── User/
│           │   ├── Controllers/
│           │   │   └── UserController.php
│           │   ├── Requests/
│           │   │   ├── UserQueryRequest.php
│           │   │   ├── UserCreateRequest.php
│           │   │   └── UserUpdateRequest.php
│           │   └── Responses/
│           │       ├── UserQueryResponse.php
│           │       ├── UserCreateResponse.php
│           │       ├── UserShowResponse.php
│           │       └── UserUpdateResponse.php
│           │
│           ├── Dashboard/
│           │   ├── Controllers/
│           │   │   └── DashboardController.php
│           │   └── Responses/
│           │       ├── DashboardStatsResponse.php
│           │       ├── SalesChartResponse.php
│           │       └── LowStockAlertResponse.php
│           │
│           └── Shared/
│               └── Middleware/
│                   └── CheckPermission.php
│
└── Providers/
    ├── AppServiceProvider.php
    └── RepositoryServiceProvider.php

database/
├── migrations/
├── seeders/
└── factories/

routes/
├── api.php
└── backoffice.php
```

---

## 2. DATABASE SCHEMA

### Tables Overview

| Table | Description |
|-------|-------------|
| `categories` | Product categories |
| `products` | Main product data |
| `product_images` | Gallery images for product |
| `stock_movements` | Stock in/out activity log |
| `sales_orders` | Order main header |
| `sales_order_items` | Line items for sales orders |
| `users` | Application users (admin/staff) |
| `roles` | User roles |
| `permissions` | User permissions |
| `role_user` | Pivot: users ↔ roles |
| `permission_role` | Pivot: roles ↔ permissions |

### ERD

```
categories (1) ────< (N) products
products (1) ────< (N) product_images
products (1) ────< (N) stock_movements
products (1) ────< (N) sales_order_items
users (1) ────< (N) stock_movements
users (1) ────< (N) sales_orders
sales_orders (1) ────< (N) sales_order_items
users (N) >────< (N) roles
roles (N) >────< (N) permissions
```

### Table: categories

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| name | varchar(255) | NOT NULL |
| slug | varchar(255) | UNIQUE |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | NULL |

### Table: products

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| category_id | uuid | FK → categories.id |
| name | varchar(255) | NOT NULL |
| slug | varchar(255) | UNIQUE |
| description | text | NULL |
| price | decimal(12,2) | NOT NULL |
| thumbnail_path | varchar(255) | NULL |
| stock | int | DEFAULT 0 |
| status | varchar(50) | available/low_stock/out_of_stock |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | NULL |

### Table: product_images

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| product_id | uuid | FK → products.id |
| image_path | varchar(255) | NOT NULL |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | NULL |

### Table: stock_movements

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| product_id | uuid | FK → products.id |
| user_id | uuid | FK → users.id |
| type | varchar(10) | in/out |
| quantity | int | NOT NULL |
| note | text | NULL |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | NULL |

### Table: sales_orders

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| user_id | uuid | FK → users.id |
| total_amount | decimal(12,2) | NOT NULL |
| status | varchar(50) | pending/paid/void |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | NULL |

### Table: sales_order_items

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| sales_order_id | uuid | FK → sales_orders.id |
| product_id | uuid | FK → products.id |
| quantity | int | NOT NULL |
| price | decimal(12,2) | NOT NULL |
| subtotal | decimal(12,2) | NOT NULL |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | NULL |

### Table: users

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| name | varchar(255) | NOT NULL |
| email | varchar(255) | UNIQUE |
| password | varchar(255) | NOT NULL |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | NULL |

### Table: roles

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| name | varchar(255) | NOT NULL |
| guard_name | varchar(255) | NOT NULL |
| created_at | timestamp | |
| updated_at | timestamp | |

### Table: permissions

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| name | varchar(255) | NOT NULL |
| guard_name | varchar(255) | NOT NULL |
| created_at | timestamp | |
| updated_at | timestamp | |

### Table: role_user (Pivot)

| Column | Type | Constraints |
|--------|------|-------------|
| role_id | uuid | FK → roles.id |
| user_id | uuid | FK → users.id |

### Table: permission_role (Pivot)

| Column | Type | Constraints |
|--------|------|-------------|
| permission_id | uuid | FK → permissions.id |
| role_id | uuid | FK → roles.id |

---

## 3. LAYERS EXPLANATION

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                        │
│         (Controllers, Requests, Responses)                   │
├─────────────────────────────────────────────────────────────┤
│                      DOMAIN LAYER                            │
│         (Repositories, Services, Observers, Enums)           │
├─────────────────────────────────────────────────────────────┤
│                      MODELS LAYER                            │
│                   (Eloquent Models)                          │
└─────────────────────────────────────────────────────────────┘
```

### Models Layer (`app/Models/`)
- Eloquent Models (database ORM)
- Relationships, fillable, casts

### Domain Layer (`app/Domain/`)
Setiap bounded context berisi:
- **Repositories/** - Data access layer
- **Services/** - Business logic
- **Observers/** - Model event listeners
- **Enums/** - Domain enumerations

Structure:
- **API/** - Auth, Profile
- **Backoffice/** - Category, Product, Inventory, Sales, User, Dashboard
- **Shared/** - BaseRepository, common Enums

### Presentation Layer (`app/Presentation/`)
- **Controllers/** - HTTP request handling, calls Service directly
- **Requests/** - Input validation (FormRequest)
- **Responses/** - Output formatting (JsonResource)

---

## 4. API ENDPOINTS

### Auth (`/api/v1/auth`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/login` | Login, return access token |
| POST | `/register` | Register new user |
| POST | `/logout` | Revoke access token |
| GET | `/me` | Get authenticated user info |

### Profile (`/api/v1/profile`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Get current user profile |
| PUT | `/` | Update current user profile |

### Category (`/api/v1/backoffice/categories`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List categories (paginated) |
| POST | `/` | Create category |
| GET | `/{id}` | Show category detail |
| PUT | `/{id}` | Update category |
| DELETE | `/{id}` | Delete category |

### Product (`/api/v1/backoffice/products`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List products (paginated) |
| POST | `/` | Create product |
| GET | `/{id}` | Show product detail |
| PUT | `/{id}` | Update product |
| DELETE | `/{id}` | Delete product |

### Inventory (`/api/v1/backoffice/stock-movements`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List stock movements (paginated) |
| POST | `/` | Create stock movement (in/out) |
| GET | `/{id}` | Show stock movement detail |

### Sales Order (`/api/v1/backoffice/sales-orders`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List sales orders (paginated) |
| POST | `/` | Create sales order |
| GET | `/{id}` | Show sales order detail |
| PUT | `/{id}` | Update sales order status |

### User (`/api/v1/backoffice/users`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List users (paginated) |
| POST | `/` | Create user |
| GET | `/{id}` | Show user detail |
| PUT | `/{id}` | Update user |
| DELETE | `/{id}` | Delete user |

### Dashboard (`/api/v1/backoffice/dashboard`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/stats` | Get dashboard statistics |
| GET | `/sales-chart` | Get sales chart data |
| GET | `/low-stock-alerts` | Get low stock product alerts |

---

## 5. IMPLEMENTATION STEPS

### Phase 1: Project Setup & Configuration
- [ ] Configure `.env` untuk PostgreSQL (Docker)
- [ ] Install dependencies: `laravel/sanctum`
- [ ] Publish Sanctum config & migration
- [ ] Setup `config/auth.php` untuk Sanctum guards
- [ ] Buat folder structure (Domain, Presentation, dll)

### Phase 2: Database - Migrations & Models
- [ ] Modify `users` migration (UUID, soft deletes)
- [ ] Create migration: `categories`
- [ ] Create migration: `products`
- [ ] Create migration: `product_images`
- [ ] Create migration: `stock_movements`
- [ ] Create migration: `sales_orders`
- [ ] Create migration: `sales_order_items`
- [ ] Create migration: `roles`
- [ ] Create migration: `permissions`
- [ ] Create migration: `role_user` (pivot)
- [ ] Create migration: `permission_role` (pivot)
- [ ] Create Model: `Category` (HasUuids, SoftDeletes, relationships)
- [ ] Create Model: `Product` (HasUuids, SoftDeletes, relationships)
- [ ] Create Model: `ProductImage`
- [ ] Create Model: `StockMovement`
- [ ] Create Model: `SalesOrder`
- [ ] Create Model: `SalesOrderItem`
- [ ] Create Model: `Role`
- [ ] Create Model: `Permission`
- [ ] Update Model: `User` (HasUuids, SoftDeletes, relationships)
- [ ] Run migrations

### Phase 3: Domain Layer - Shared
- [ ] Create `BaseRepository.php`
- [ ] Create `StatusType.php` enum

### Phase 4: Auth & Profile Module
- [ ] Create `AuthRepository.php`
- [ ] Create `AuthService.php`
- [ ] Create `ProfileService.php`
- [ ] Create `AuthController.php`
- [ ] Create `AuthLoginRequest.php`
- [ ] Create `AuthRegisterRequest.php`
- [ ] Create `AuthLoginResponse.php`
- [ ] Create `AuthRegisterResponse.php`
- [ ] Create `AuthMeResponse.php`
- [ ] Create `ProfileController.php`
- [ ] Create `ProfileUpdateRequest.php`
- [ ] Create `ProfileShowResponse.php`
- [ ] Create `ProfileUpdateResponse.php`
- [ ] Setup routes di `routes/api.php`

### Phase 5: Category Module
- [ ] Create `CategoryRepository.php`
- [ ] Create `CategoryService.php`
- [ ] Create `CategoryObserver.php`
- [ ] Create `CategoryController.php`
- [ ] Create `CategoryQueryRequest.php`
- [ ] Create `CategoryCreateRequest.php`
- [ ] Create `CategoryUpdateRequest.php`
- [ ] Create `CategoryQueryResponse.php`
- [ ] Create `CategoryCreateResponse.php`
- [ ] Create `CategoryShowResponse.php`
- [ ] Create `CategoryUpdateResponse.php`
- [ ] Register Observer di `AppServiceProvider`
- [ ] Setup routes di `routes/backoffice.php`

### Phase 6: Product Module
- [ ] Create `ProductRepository.php`
- [ ] Create `ProductService.php`
- [ ] Create `ProductObserver.php`
- [ ] Create `ProductStatusType.php` enum
- [ ] Create `ProductController.php`
- [ ] Create `ProductQueryRequest.php`
- [ ] Create `ProductCreateRequest.php`
- [ ] Create `ProductUpdateRequest.php`
- [ ] Create `ProductQueryResponse.php`
- [ ] Create `ProductCreateResponse.php`
- [ ] Create `ProductShowResponse.php`
- [ ] Create `ProductUpdateResponse.php`
- [ ] Register Observer
- [ ] Setup routes

### Phase 7: Inventory Module (Stock Movement)
- [ ] Create `StockMovementRepository.php`
- [ ] Create `StockMovementService.php`
- [ ] Create `StockMovementObserver.php` (auto update product stock)
- [ ] Create `StockMovementType.php` enum (in/out)
- [ ] Create `StockMovementController.php`
- [ ] Create `StockMovementQueryRequest.php`
- [ ] Create `StockMovementCreateRequest.php`
- [ ] Create `StockMovementQueryResponse.php`
- [ ] Create `StockMovementCreateResponse.php`
- [ ] Create `StockMovementShowResponse.php`
- [ ] Register Observer
- [ ] Setup routes

### Phase 8: Sales Module
- [ ] Create `SalesOrderRepository.php`
- [ ] Create `SalesOrderItemRepository.php`
- [ ] Create `SalesOrderService.php`
- [ ] Create `SalesOrderObserver.php`
- [ ] Create `OrderStatusType.php` enum (pending/paid/void)
- [ ] Create `SalesOrderController.php`
- [ ] Create `SalesOrderQueryRequest.php`
- [ ] Create `SalesOrderCreateRequest.php`
- [ ] Create `SalesOrderUpdateRequest.php`
- [ ] Create `SalesOrderQueryResponse.php`
- [ ] Create `SalesOrderCreateResponse.php`
- [ ] Create `SalesOrderShowResponse.php`
- [ ] Create `SalesOrderUpdateResponse.php`
- [ ] Register Observer
- [ ] Setup routes

### Phase 9: User Module
- [ ] Create `UserRepository.php`
- [ ] Create `UserService.php`
- [ ] Create `UserObserver.php`
- [ ] Create `RoleType.php` enum
- [ ] Create `PermissionType.php` enum
- [ ] Create `UserController.php`
- [ ] Create `UserQueryRequest.php`
- [ ] Create `UserCreateRequest.php`
- [ ] Create `UserUpdateRequest.php`
- [ ] Create `UserQueryResponse.php`
- [ ] Create `UserCreateResponse.php`
- [ ] Create `UserShowResponse.php`
- [ ] Create `UserUpdateResponse.php`
- [ ] Create `CheckPermission.php` middleware
- [ ] Register Observer & Middleware
- [ ] Setup routes

### Phase 10: Dashboard Module
- [ ] Create `DashboardService.php`
- [ ] Create `DashboardController.php`
- [ ] Create `DashboardStatsResponse.php`
- [ ] Create `SalesChartResponse.php`
- [ ] Create `LowStockAlertResponse.php`
- [ ] Setup routes

### Phase 11: Seeders & Testing
- [ ] Create `RoleSeeder.php`
- [ ] Create `PermissionSeeder.php`
- [ ] Create `UserSeeder.php` (Admin, Staff, Viewer)
- [ ] Create `CategorySeeder.php`
- [ ] Create `ProductSeeder.php`
- [ ] Run seeders
- [ ] Test semua endpoints dengan Postman/Insomnia
