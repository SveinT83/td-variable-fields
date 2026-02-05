# TD Variable Fields – Plugin Scope & Architecture

## Purpose

TD Variable Fields is a WordPress plugin whose sole responsibility is to manage **global custom variables**.
These variables are intended to be reused across the entire WordPress installation (frontend, templates, shortcodes, PHP).

The plugin must remain:

* lightweight
* framework-agnostic
* dependency-free (no external plugins required)

This plugin replaces the need for ACF Options Pages or similar paid solutions.

---

## What This Plugin IS

* A global custom fields manager
* A key → value store for reusable variables
* A simple admin UI for managing variables
* A helper layer for retrieving variables safely

Supported variable types (initial scope):

* text (string)
* number (int/float)
* boolean (true/false)

---

## What This Plugin IS NOT

* NOT a pricing engine
* NOT a settings framework
* NOT business logic
* NOT a form builder
* NOT post meta or taxonomy meta
* NOT a database abstraction layer

All business meaning lives **outside** this plugin.

---

## Data Storage Rules

* All variables are stored in **ONE WordPress option**
* Option name (fixed):

```
td_variable_fields
```

Structure:

```php
[
  'variable_key' => [
    'type'  => 'text|number|bool',
    'value' => mixed,
    'description' => string|null
  ]
]
```

Rules:

* No custom database tables
* No multiple options
* No post meta
* No serialization outside WordPress core

---

## Architecture Rules

* Namespaced PHP only
* OOP-based, but simple
* No static state
* No global functions except optional helper wrappers
* No external services

Namespace root:

```
TD\VariableFields
```

---

## Folder Responsibilities

```
src/
├─ Core/        → plugin bootstrap, hooks, lifecycle
├─ Admin/       → admin UI, forms, validation
├─ Helpers/     → safe accessors (get variable)
```

No frontend rendering logic belongs here.

---

## Admin UI Requirements

* Single admin page under:
  Settings → Variable Fields
* Capabilities: `manage_options`
* Must support:

    * list variables
    * add variable
    * edit variable
    * delete variable
* No AJAX required for v1
* Use standard WordPress admin UI components

---

## Public API (Planned)

The plugin must expose a stable retrieval method.

Example (subject to final implementation):

```php
td_var('nextcloud_10gb_price');
```

or

```php
Variable::get('nextcloud_10gb_price');
```

Formatting, currency, or output logic is NOT handled here.

---

## Security & Validation

* All input sanitized on save
* All output escaped on render
* Nonces required for admin actions
* No direct file access

---

## Versioning Philosophy

* v1 = stable core
* Backward compatibility is required for variable keys
* Schema changes must be additive only

---

## Final Rule

If a feature introduces:

* business logic
* pricing rules
* presentation concerns
* dependency on another plugin

Then it **does not belong in this plugin**.
