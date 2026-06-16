# WPZylos Config

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![GitHub](https://img.shields.io/badge/GitHub-KYNetCode-181717?logo=github)](https://github.com/KYNetCode/wpzylos-config)

Configuration management with dot-notation and .env support for WPZylos framework.

📖 **[Full Documentation](https://wpzylos.com)** | 🐛 **[Report Issues](https://github.com/KYNetCode/wpzylos-config/issues)**

---

## ✨ Features

- **Dot-notation Access** — Read and write nested values with `get('app.name')`
- **Environment Support** — Parse `.env` files with quote and escape handling
- **Type Safety** — Typed accessors: `string()`, `int()`, `float()`, `bool()`, `array()`
- **Directory Loading** — Auto-load all PHP config files from a directory
- **Deep Merging** — Merge configuration arrays recursively

---

## 📋 Requirements

| Requirement | Version |
| ----------- | ------- |
| PHP         | ^8.0    |

---

## 🚀 Installation

```bash
composer require KYNetCode/wpzylos-config
```

---

## 📖 Quick Start

```php
use WPZylos\Framework\Config\ConfigRepository;

$config = new ConfigRepository([
    'app' => [
        'name' => 'My Plugin',
        'debug' => true,
    ],
    'database' => [
        'prefix' => 'myplugin_',
    ],
]);

// Dot-notation access
$config->get('app.name');           // 'My Plugin'
$config->get('app.debug');          // true
$config->get('missing', 'default'); // 'default'

// Typed accessors
$config->string('app.name');        // 'My Plugin'
$config->bool('app.debug');         // true
$config->int('database.port', 3306); // 3306
```

---

## 🏗️ Core Features

### Dot-notation Access

```php
$config->get('database.connections.mysql.host');
$config->set('database.connections.mysql.port', 3307);
$config->has('database.connections.mysql'); // true
```

### Typed Accessors

```php
$config->string('app.name');            // Returns string, casts if needed
$config->int('database.port', 3306);    // Returns int
$config->float('rate.tax', 0.15);       // Returns float
$config->bool('app.debug');             // Handles 'true', '1', 'yes', 'on'
$config->array('app.providers', []);    // Returns array or default
```

### Loading from a Directory

```php
$config = new ConfigRepository();
$config->loadDirectory(__DIR__ . '/config');

// Each PHP file becomes a top-level key:
// config/app.php    → $config->get('app.key')
// config/database.php → $config->get('database.key')
```

### Environment Variables

```php
use WPZylos\Framework\Config\EnvLoader;

$env = new EnvLoader();
$env->load(__DIR__ . '/.env');

// Instance methods
$env->get('APP_DEBUG');     // Value from .env
$env->has('DB_HOST');       // true/false
$env->all();                // All parsed values

// Static helper (checks $_ENV, then getenv())
EnvLoader::env('APP_DEBUG', false);
```

### Deep Merging

```php
$config->merge([
    'app' => ['version' => '2.0'],
]);
// Existing app values preserved, 'version' added
```

---

## 📦 Related Packages

| Package                                                                  | Description            |
| ------------------------------------------------------------------------ | ---------------------- |
| [wpzylos-core](https://github.com/KYNetCode/wpzylos-core)           | Application foundation |
| [wpzylos-container](https://github.com/KYNetCode/wpzylos-container) | Dependency injection   |
| [wpzylos-scaffold](https://github.com/KYNetCode/wpzylos-scaffold)   | Plugin template        |

---

## 📖 Documentation

For comprehensive documentation, tutorials, and API reference, visit **[wpzylos.com](https://wpzylos.com)**.

---

## ☕ Support the Project

- [GitHub Sponsors](https://github.com/sponsors/KYNetCode)
- [PayPal Donate](https://www.paypal.com/donate/?hosted_button_id=66U4L3HG4TLCC)

---

## 📄 License

MIT License. See [LICENSE](LICENSE) for details.

---

## 🤝 Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

---

**Made with ❤️ by [KYNetCode](https://github.com/KYNetCode)**
