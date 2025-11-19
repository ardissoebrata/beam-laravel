# Beam Laravel Application

A modern Laravel application built with Inertia.js, Vue 3, TypeScript, PrimeVue, and role-based access control. Enhanced with Laravel Boost for improved developer experience.

## Features

### Authentication
- Complete authentication system (Login, Register, Password Reset, Email Verification)
- Powered by Laravel Breeze with Inertia.js and Vue 3
- Session-based authentication with Laravel Sanctum

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
- **Vue 3** with Composition API and `<script setup>`
- **TypeScript** for type safety
- **PrimeVue 4** UI components with Aura theme
- **Tailwind CSS 3** for styling (configured to use PrimeVue CSS variables)
- **Vee-Validate** + **Zod** for form validation
- **Inertia.js v2** for seamless SPA experience
- Sticky navigation with responsive design

### Laravel Boost
- **AI-powered Laravel development assistant** integrated via MCP (Model Context Protocol)
- Database schema access and queries
- Artisan command execution
- Error log monitoring
- Tinker execution
- Version-specific documentation search
- Enhanced with GitHub Copilot for smart code generation

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
# Terminal 1: Start Vite dev server
npm run dev

# Terminal 2: Start Laravel server
php artisan serve
```

Visit: http://localhost:8000

### Build for Production
```bash
npm run build
```

## Laravel Boost Integration

This project uses **Laravel Boost**, an MCP server that provides AI-powered assistance for Laravel development.

### What is Laravel Boost?

Laravel Boost is a Model Context Protocol (MCP) server that gives GitHub Copilot deep access to your Laravel application, enabling intelligent code generation and assistance specific to your project's configuration.

### Available Boost Tools

#### Application Info
```
Get comprehensive application information including PHP version, Laravel version, database engine, all installed packages with versions, and Eloquent models.
```

#### Database Operations
- **list-database-connections**: View configured database connections
- **database-query**: Execute read-only SQL queries
- **read-database-schema**: Get detailed database schema including tables, columns, types, and relationships

#### Artisan Commands
- **list-artisan-commands**: List all available Artisan commands with parameters
- **run-artisan-command**: Execute Artisan commands (e.g., migrations, seeders)

#### Debugging & Logs
- **browser-logs**: Read browser console logs for frontend debugging
- **read-application-logs**: Access Laravel application logs
- **read-last-error**: Get details of the last error/exception

#### Code Execution
- **tinker**: Execute PHP code in Laravel application context (like `php artisan tinker`)

#### Documentation
- **search-docs**: Search version-specific Laravel ecosystem documentation (Laravel, Inertia, Livewire, Filament, PrimeVue, Pest, etc.)

#### Configuration
- **list-routes**: Display all defined routes including Folio routes
- **list-config-keys**: Show available configuration keys
- **get-config**: Get specific config values using dot notation
- **get-absolute-url**: Generate absolute URLs for routes or paths

### How to Use Laravel Boost

1. **Via GitHub Copilot Chat**: Simply ask questions or request code generation
   ```
   "Show me all users with admin role using Eloquent"
   "Create a new migration for posts table"
   "What's the database schema for users table?"
   ```

2. **Automatic Context**: Boost automatically provides:
   - Installed package versions (ensures version-specific code)
   - Database schema (for accurate queries)
   - Artisan commands (for proper command usage)
   - Application configuration

3. **Best Practices**: 
   - Boost follows Laravel conventions and your project's coding style
   - Validates code against your specific package versions
   - Searches official documentation before making suggestions

### Boost Configuration

The `boost.json` file in the project root configures Laravel Boost settings. You can customize:
- Ignored paths
- Documentation preferences
- MCP server settings

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

- **DataTable** - User listing with sorting, filtering, and pagination
- **Button** - Actions and form submissions
- **InputText** - Text inputs
- **Password** - Password inputs with toggle visibility
- **Select** - Role selection dropdown
- **Message** - Validation error messages (configured with `size="small"` and `variant="simple"`)
- **Toast** - Success/error notifications
- **ConfirmDialog** - Delete confirmations
- **Dropdown** - User menu and settings

### PrimeVue Theming

- Uses **Aura** theme preset
- Primary color integrated with Tailwind CSS via CSS variables
- Tailwind classes like `bg-primary-50`, `text-primary-600`, `border-primary` map to PrimeVue's CSS variables (`--p-primary-50`, `--p-primary-600`, etc.)

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
│   │   │   ├── Auth/                          # Breeze auth controllers
│   │   │   └── UserController.php             # User management CRUD
│   │   ├── Middleware/
│   │   │   └── EnsureUserIsAdmin.php          # Admin role middleware
│   │   └── Requests/                          # Form request validation
│   ├── Models/
│   │   └── User.php                           # User model with roles
│   └── Providers/
│       └── AppServiceProvider.php             # Service provider
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   ├── ApplicationLogo.vue            # App logo component
│   │   │   ├── Dropdown.vue                   # Dropdown component
│   │   │   ├── NavLink.vue                    # Navigation link (primary colors)
│   │   │   └── ResponsiveNavLink.vue          # Mobile nav link
│   │   ├── Layouts/
│   │   │   ├── AuthenticatedLayout.vue        # Main app layout (sticky nav)
│   │   │   └── GuestLayout.vue                # Guest pages layout
│   │   ├── Pages/
│   │   │   ├── Auth/                          # Login, Register, etc.
│   │   │   ├── Profile/                       # User profile management
│   │   │   ├── Users/                         # User management (admin)
│   │   │   ├── Dashboard.vue                  # Dashboard page
│   │   │   └── Welcome.vue                    # Landing page
│   │   ├── types/
│   │   │   └── index.d.ts                     # TypeScript type definitions
│   │   ├── utils/
│   │   │   └── validation.ts                  # Zod validation schemas
│   │   ├── app.ts                             # Vue app initialization
│   │   └── bootstrap.ts                       # Axios configuration
│   ├── css/
│   │   └── app.css                            # Global styles + Tailwind
│   └── views/
│       └── app.blade.php                      # Main HTML template
├── routes/
│   ├── web.php                                # Web routes
│   ├── auth.php                               # Auth routes
│   └── console.php                            # Console routes
├── database/
│   ├── migrations/
│   │   └── *_add_role_to_users_table.php      # Role column migration
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   └── AdminUserSeeder.php                # Seeds admin & member users
│   └── factories/
│       └── UserFactory.php
├── bootstrap/
│   ├── app.php                                # Application bootstrap
│   └── providers.php                          # Service providers
├── config/                                    # Laravel configuration
├── boost.json                                 # Laravel Boost configuration
├── tailwind.config.js                         # Tailwind with PrimeVue colors
├── tsconfig.json                              # TypeScript configuration
├── vite.config.js                             # Vite build configuration
└── composer.json                              # PHP dependencies
```

## Key Features & Configurations

### Sticky Navigation
Both main navigation bar and page headings are sticky, staying visible when scrolling.

### Tailwind + PrimeVue Integration
Tailwind is configured to recognize PrimeVue's CSS variables, allowing usage of classes like:
- `bg-primary-50`, `bg-primary-100`, etc.
- `text-primary`, `text-primary-600`, etc.
- `border-primary-700`, etc.

These automatically use PrimeVue theme colors via CSS variables (`--p-primary-*`).

### Form Validation
All forms use small, simple variant error messages for a clean UI.

## Tech Stack

### Backend
- **Laravel** 12.x - PHP framework
- **PHP** 8.2+ - Programming language
- **Laravel Sanctum** 4.x - Authentication
- **Laravel Breeze** 2.x - Authentication scaffolding
- **Ziggy** 2.x - Route helpers for JavaScript
- **Laravel Boost** 1.8+ - AI development assistant (dev)

### Frontend
- **Vue 3** - Progressive JavaScript framework
- **TypeScript** 5.x - Type-safe JavaScript
- **Inertia.js** 2.x - Modern monolith SPA framework
- **PrimeVue** 4.4+ - Vue UI component library
- **Tailwind CSS** 3.x - Utility-first CSS framework
- **Vee-Validate** 4.x - Form validation
- **Zod** - TypeScript-first schema validation
- **PrimeIcons** 7.x - Icon library
- **Vite** 7.x - Build tool and dev server

### Developer Tools
- **Laravel Pint** - PHP code style fixer
- **PHPUnit** 11.x - PHP testing framework
- **Faker** - Fake data generation
- **Vue TypeScript Compiler** - Type checking for Vue
