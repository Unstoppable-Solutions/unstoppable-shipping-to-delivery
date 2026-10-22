# Unstoppable Shipping to Delivery for WooCommerce

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/unstoppable-shipping-to-delivery.svg)](https://wordpress.org/plugins/unstoppable-shipping-to-delivery/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/unstoppable-shipping-to-delivery.svg)](https://wordpress.org/plugins/unstoppable-shipping-to-delivery/)
[![License: GPL v2+](https://img.shields.io/badge/License-GPLv2+-blue.svg)](https://www.gnu.org/licenses/gpl-2.0)

Automatically replaces "Shipping" with "Delivery" everywhere WooCommerce says it on the frontend — cart, checkout, order emails, account pages, confirmations. Activate and go: no settings to configure.

> **End users:** install from the WordPress Plugin Directory — [wordpress.org/plugins/unstoppable-shipping-to-delivery](https://wordpress.org/plugins/unstoppable-shipping-to-delivery/)

## Why it exists

Not every store ships. Local-pickup, in-house courier, service-based and food businesses all run WooCommerce, and the hard-coded word "Shipping" doesn't match what they actually do. This plugin fixes that in one activation without you editing templates or wrangling language files.

## Features

- **Frontend-wide rewrite** — cart, checkout, order emails, My Account, confirmations
- **Zero configuration** — install, activate, done
- **No template changes** — doesn't touch your theme
- **Translation-aware** — plays nicely with WPML / Polylang
- **No performance hit** — single filter hook, no DB queries

## Development

### Release workflow

Releases are deployed to WordPress.org automatically by GitHub Actions:

1. Finish the feature work on `main`
2. Bump the version in `unstoppable-shipping-to-delivery.php` (header + any constant), `readme.txt` (Stable tag), and add a Changelog entry
3. Commit and push `main`
4. On GitHub, create a new **Release** tagged `vX.Y.Z` — the deploy workflow fires and pushes to WordPress.org SVN

### CI

Every push and pull request runs the official WordPress Plugin Check against the codebase.

## License

GPL-2.0-or-later. See the `License` header in `unstoppable-shipping-to-delivery.php`.

## Author

Developed and maintained by [Unstoppable Solutions](https://unstoppablesolutions.app).
