# HAPI PHP Framework

HAPI is a PHP-based web application framework with a custom MVC-like architecture. It includes user authentication and role-based access control.

## Table of Contents

- [Overview](#overview)
- [Architecture](#architecture)
- [Directory Structure](#directory-structure)
- [Entry Points](#entry-points)
- [Core Components](#core-components)
- [Domain Modules](#domain-modules)
- [Configuration](#configuration)
- [Database](#database)
- [Third-Party Dependencies](#third-party-dependencies)
- [Development Notes](#development-notes)
- [License](#license)

## Overview

- **Language**: PHP (pre-namespace, procedural/OO mix)
- **Database**: MySQL/MariaDB via MySQLi with ability to plug-in others (primary; SQLite drivers exist but unused)
- **Architecture**: Custom framework with Drivers, Handlers (data access/business logic), Modules (controllers), and entry points
- **Frontend**: HTML/CSS/JS with jQuery, TinyMCE, datepickers, etc.
- **Session**: Custom database-backed session handler
- **Authentication**: Username/email login with Argon2i password hashing, token-based sessions with TAN (Transaction Authentication Number) support

## Architecture

HAPI follows a layered architecture:

1. **Drivers** (`Drivers/`) - Low-level abstractions for database, config, email, files, etc.
2. **Handlers** (`Handlers/`) - Domain models/data access objects extending `BaseHandler` (Active Record pattern)
3. **Modules** (`Modules/`) - Application logic/controllers extending `AbstractModule`, organized by feature
4. **Entry Points** - `index.php` (main app), `api.php` (AJAX/API), `setup.php` (DB setup)

### Data Flow

```
Request → index.php/api.php
         ↓
      InitModule (bootstrap)
         ↓
      SecurityModule (auth/authorization)
         ↓
      Module (business logic)
         ↓
      Handler (data access)
         ↓
      Driver (DB/filesystem)
```

## Directory Structure

```
HAPI/
├── Drivers/           # Low-level drivers (DB, config, email, files)
├── Handlers/          # Domain models/handlers (data layer)
│   ├── General/       # Core handlers (BaseHandler, settings, apps, etc.)
│   ├── Users/         # User management, auth, roles, groups
│   ├── Forms/         # Form builder and workflow engine
│   └── Util/          # Utilty classes
├── Modules/           # Application modules/controllers
│   ├── AbstractModule.php
│   ├── User/          # Login, logout, profile, password
│   ├── Layout/        # Header, footer and other front-end modules
│   ├── Navigation/    # Menus
│   ├── Omega /        # Administration interface modules
│   ├── Utils/         # Security, session, debug, benchmark
│   └── API/           # API endpoints
├── Views/             # View templates (referenced by modules)
├── css/               # Stylesheets
├── js/                # JavaScript
├── images/            # Images and assets
├── 3rdParty/          # Third-party libraries (PHPMailer, Google API client)
├── index.php          # Login page entry point
├── api.php            # API/AJAX endpoint
└── setup.php          # Database setup script
```

## Entry Points

| File | Purpose | Description |
|---|---|---|
| `index.php` | Main application | Authenticated interface, loads `InitModule`, `SecurityModule`, `MainMenuModule`, and dynamic app module based on request |
| `api.php` | API endpoints | Handles AJAX calls via `obj` and `params` parameters, loads `Modules/API/m_{OBJECT}.php` |
| `setup.php` | Database setup | Creates tables from handler `DB_FIELDS` definitions |

## Core Components

### Drivers

| Driver | Class | Purpose |
|---|---|---|
| `MysqliDriver.php` | `MysqliDriver` | **Primary DB driver** - MySQL/MariaDB via MySQLi with query branching, benchmarking, partitioning support |
| `ConfigDriver.php` | `ConfigDriver` | Settings manager with DB persistence (table `Settings`) |
| `MemArrDriver.php` | `memArrDriver` | In-memory key-value store (`CATEGORY:SETTING` format) |
| `CookieDriver.php` | `CookieDriver` | Cookie-based settings storage |
| `PHPMailerDriver.php` | `EmailDriver` | SMTP email via PHPMailer (active) |
| `EmailDriver.php` | `EmailDriver` | Native PHP `mail()` email (alternative) |
| `FileDriver.php` | `FileDriver` | Filesystem operations, uploads, file listing |
| `XMLTextDriver.php` | `XMLAttribute`, `XMLNode` | Lightweight XML DOM objects |
| `SQLiteDriver.php`, `d_sqlite3.php` | `SQLiteDriver`, `ArchiveDBDriver` | SQLite drivers (unused in current codebase) |

### Handlers

All domain handlers extend `BaseHandler` (`Handlers/General/BaseHandler.php`), which provides Active Record pattern functionality. Forms handlers use `GenericDataHandler` (different base class).

### Modules

**AbstractModule** provides `run($PARAMETERS)` and `display($PARAMETERS)`. Key modules include init bootstrap, authentication, security/authorization, session management, and API endpoints.

## Domain Modules

| Domain | Description |
|---|---|
| **Users** | Authentication (Argon2i), sessions with TAN, RBAC, groups, email validation |
| **Omega** | Admin interface for user and site management |

## Configuration

Settings managed via `ConfigDriver`, `memArrDriver`, and `CookieDriver`. Accessed through registry as `CATEGORY:SETTING` pairs. Key settings include site config, auth, encryption pepper, database credentials, and session data.

## Database

- **Primary**: MySQL/MariaDB via `MysqliDriver`
- **Tables**: All have `ID` (int unsigned auto_increment) and `SRID` (char(10), auto-generated on insert)
- **Setup**: Run `setup.php?createdb=1` to generate tables from handler `DB_FIELDS`
- **Schema DSL**: Handlers define fields with types like `integer`, `bool`, `char|N`, `varchar|N`, `text`, `date`, `datetime`, `enum|...`, `money`

## Third-Party Dependencies

Located in `3rdParty/`: jQuery, PHPMailer, Google API PHP Client, DatePickerControl, TinyMCE.

## Development Notes

- Pre-namespace PHP (PHP 4/5 era style)
- Custom session handler using `sessions` table
- Passwords hashed with `password_hash()` using Argon2i + pepper
- TAN-based session validation
- HTML encoding on input, decoding on output
- Some code paths reference outdated directory structures
- Debug/benchmark utilities available

## License

Copyright (c) 2011 Carlos Ferrera. All Rights Reserved.  
Dissemination, reproduction, or use requires prior written permission from Carlos Ferrera.  
Contact: irondogg@gmail.com
