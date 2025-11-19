# Laravel + PrimeVue + TypeScript Application

This Laravel application has been set up with Laravel Breeze, Inertia.js, Vue 3, TypeScript, PrimeVue, and role-based permissions.

## Features

### Authentication
- Complete authentication system (Login, Register, Password Reset, Email Verification)
- Powered by Laravel Breeze with Inertia.js and Vue 3

### Role-Based Access Control
- Two user roles: **Admin** and **Member**
- Admin middleware for protecting routes
- Role methods on User model (`isAdmin()`, `isMember()`)

### User Management (Admin Only)
- Create, Read, Update, and Delete users
- Assign roles to users
- Admin-only access via middleware
- Located at `/users` route

### Frontend Stack
- **Vue 3** with Composition API
- **TypeScript** for type safety
- **PrimeVue** UI components with Aura theme
- **Tailwind CSS** for styling
- **Vee-Validate** + **Zod** for form validation
- **Inertia.js** for seamless SPA experience

## Default Users

Two test users are seeded by default:

**Admin User:**
- Email: admin@example.com
- Password: password
- Role: admin

**Member User:**
- Email: member@example.com
- Password: password
- Role: member

## Development

### Install Dependencies
```bash
composer install
npm install
```

### Setup Environment
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Run Development Server
```bash
npm run dev
php artisan serve
```

Visit: http://localhost:8000

### Build for Production
```bash
npm run build
```

## Key Files

### Backend
- `app/Models/User.php` - User model with role methods
- `app/Http/Controllers/UserController.php` - User CRUD controller
- `app/Http/Middleware/EnsureUserIsAdmin.php` - Admin middleware
- `database/migrations/*_add_role_to_users_table.php` - Role migration
- `routes/web.php` - Route definitions

### Frontend
- `resources/js/Pages/Users/` - User management pages
  - `Index.vue` - User list with DataTable
  - `Create.vue` - Create user form
  - `Edit.vue` - Edit user form
- `resources/js/utils/validation.ts` - Zod validation schemas
- `resources/js/app.ts` - Main Vue app with PrimeVue setup
- `resources/js/Layouts/AuthenticatedLayout.vue` - Main layout with navigation

## Middleware

### Available Middleware Aliases
- `auth` - Requires authenticated user
- `admin` - Requires admin role
- `verified` - Requires email verification

### Usage Example
```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});
```

## PrimeVue Components Used

- DataTable - User listing
- Button - Actions and forms
- InputText - Text inputs
- Password - Password inputs with toggle
- Select (Dropdown) - Role selection
- Message - Validation error messages
- Toast - Success/error notifications
- ConfirmDialog - Delete confirmations

## Form Validation

All forms use Vee-Validate with Zod schemas for robust validation:

```typescript
// Example schema
const userSchema = z.object({
    name: z.string().min(1).max(255),
    email: z.string().email(),
    role: z.enum(['admin', 'member']),
});
```

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/          # Breeze auth controllers
│   │   │   └── UserController.php
│   │   └── Middleware/
│   │       └── EnsureUserIsAdmin.php
│   └── Models/
│       └── User.php
├── resources/
│   ├── js/
│   │   ├── Components/        # Reusable Vue components
│   │   ├── Layouts/           # Layout components
│   │   ├── Pages/             # Page components
│   │   │   ├── Auth/          # Authentication pages
│   │   │   ├── Profile/       # User profile pages
│   │   │   └── Users/         # User management pages
│   │   ├── types/             # TypeScript definitions
│   │   ├── utils/             # Utility functions
│   │   └── app.ts             # Main application file
│   └── css/
│       └── app.css
├── routes/
│   └── web.php                # Route definitions
└── database/
    ├── migrations/
    └── seeders/
        └── AdminUserSeeder.php
```

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** Vue 3 + TypeScript
- **UI Framework:** PrimeVue 4 + Tailwind CSS 4
- **SPA:** Inertia.js
- **Validation:** Vee-Validate + Zod
- **Icons:** PrimeIcons
- **Build Tool:** Vite 7
