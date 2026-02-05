# TD Variable Fields

TD Variable Fields is a lightweight WordPress plugin designed to manage **global custom variables**. These variables can be reused throughout your entire WordPress installation (frontend, templates, shortcodes, and PHP).

It provides a simple solution for those who need centralized key-value storage without the complexity or cost of larger frameworks like ACF Options Pages.

---

## Features

- **Global Storage:** All variables are stored in a single WordPress option (`td_variable_fields`).
- **Simple Interface:** Manage variables directly from the WordPress admin dashboard.
- **Supported Types:** Text (string), Number (int/float), and Boolean (true/false).
- **Lightweight:** No external dependencies, minimal performance impact.
- **Developer Friendly:** Simple helper functions and shortcodes for data retrieval.

---

## Usage

### Shortcode
You can display a variable anywhere that supports shortcodes (e.g., in the block editor or widgets):

```text
[td_var key="my_variable_key" default="Default Value"]
```

### PHP Helper Function
For developers, there is a global function `td_var()` that can be used in theme files or other plugins:

```php
<?php
// Retrieve a value with an optional default value
$price = td_var('nextcloud_price', '199,-');

echo "The price is: " . esc_html($price);
?>
```

Alternatively, you can use the class directly:
```php
use TD\VariableFields\Helpers\Variable;

$value = Variable::get('key_name', 'default');
```

---

## Installation & Setup

1. Navigate to **Settings** -> **Variable Fields** in the WordPress menu.
2. Add your variables with a unique key, select the type, and set the value.
3. Save your changes.

---

## Requirements

- WordPress (local or staging)
- PHP >= 7.2.5
- Composer (for development)
- Node.js >= 14.8 + npm (for building assets)

---

## Development

### Installation
```bash
composer install
npm install
```

### Building Assets
```bash
# Development
npm run dev

# Production
npm run prod
```

### Testing
```bash
# Run PHPUnit tests
vendor/bin/phpunit
```

---

## Architecture
The plugin follows a modern OOP structure with PSR-4 autoloading and a Dependency Injection Container. All data is stored in an associative array within the `wp_options` table for maximum performance and minimal database noise.
