# Development Roadmap — Unstoppable Shipping to Delivery for WooCommerce

This roadmap tracks current and planned work. It is maintained alongside
development. Items marked **Completed** have shipped; everything else is
**Proposed** or **Planned** until selected for a milestone.

Each item lists: Description · Benefit · Priority · Complexity · Milestone ·
Status · Source.

---

## Phase 1 — Immediate Priorities

### Repository migration and verification
- **Description:** Confirm the plugin source, docs, and CI live in the `Unstoppable-Solutions/unstoppable-shipping-to-delivery` repo as the source of truth.
- **Benefit:** Single, authoritative development source.
- **Priority:** High · **Complexity:** Small · **Milestone:** 1.2.3
- **Status:** Completed · **Source:** Previous Discussion

### WordPress 7.1.3 compatibility metadata
- **Description:** Set `Tested up to: 7.1.3` in the plugin header and `readme.txt`, bump Stable tag to 1.2.3, add changelog/upgrade entries. (7.1.3 compatibility testing completed by the plugin owner.)
- **Benefit:** Removes the "untested with your version" warning on current WordPress.
- **Priority:** High · **Complexity:** Small · **Milestone:** 1.2.3
- **Status:** Completed · **Source:** Previous Discussion

### Fix i18n filter argument counts
- **Description:** `gettext_with_context` (4 args) and `ngettext` (5 args) are registered with only 3 accepted args, so the `$domain` check reads the wrong parameter and those two filters never match — they currently no-op. Register each with the correct arg count and a matching wrapper so `$domain` is read correctly.
- **Benefit:** Correct, predictable server-side replacement instead of relying on the JS fallback for context/plural strings. Needs a test pass, because a correct fix widens replacement coverage.
- **Priority:** High · **Complexity:** Small · **Milestone:** 1.3.0
- **Status:** Proposed · **Source:** Code Review

### Guard `is_plugin_active()` availability
- **Description:** `is_plugin_active()` lives in `wp-admin/includes/plugin.php`; guard calls with `function_exists()` or include the file before use.
- **Benefit:** Avoids a fatal if the function is called before the admin plugin API loads.
- **Priority:** Low · **Complexity:** Small · **Milestone:** 1.3.0
- **Status:** Proposed · **Source:** Code Review

### SVN publishing workflow verification
- **Description:** Confirm the org-level `SVN_USERNAME`/`SVN_PASSWORD` secrets deploy correctly and remove the stale "dormant" header note in `deploy-to-wporg.yml` (the plugin is already live on WordPress.org).
- **Benefit:** Reliable, repeatable releases from GitHub.
- **Priority:** High · **Complexity:** Small · **Milestone:** 1.2.3
- **Status:** In Progress · **Source:** Code Review

---

## Phase 2 — Short-Term Improvements

### Enqueue the JS fallback (drop inline script and jQuery dependency)
- **Description:** Move the inline `wp_footer` script into a properly enqueued asset and replace the jQuery cart/checkout hooks with native event listeners.
- **Benefit:** Meets WordPress.org script guidelines; works on block themes that dequeue jQuery.
- **Priority:** Medium · **Complexity:** Small · **Milestone:** 1.3.0
- **Status:** Proposed · **Source:** Code Review

### Debounce the MutationObserver
- **Description:** The observer re-walks the whole `<body>` on every mutation. Add a short debounce / requestAnimationFrame throttle.
- **Benefit:** Lower CPU on complex or frequently-updating pages.
- **Priority:** Medium · **Complexity:** Small · **Milestone:** 1.3.0
- **Status:** Proposed · **Source:** Code Review

### Reduce over-replacement risk in the `default` text domain
- **Description:** Replacing in the `default` domain can rewrite unrelated core/plugin strings that merely contain "shipping." Scope the replacement more tightly or make the `default`-domain pass opt-in.
- **Benefit:** Fewer false positives on non-WooCommerce text.
- **Priority:** Medium · **Complexity:** Medium · **Milestone:** 1.3.0
- **Status:** Proposed · **Source:** Code Review

### Internationalize admin strings
- **Description:** Wrap the admin-notice strings in translation functions and load the text domain.
- **Benefit:** Translatable UI; cleaner Plugin Check report.
- **Priority:** Low · **Complexity:** Small · **Milestone:** 1.3.0
- **Status:** Proposed · **Source:** Code Review

---

## Phase 3 — Mid-Term Development

### Developer filter for the replacement map
- **Description:** Expose a filter so developers can adjust or extend the word map without editing the plugin, while keeping the zero-config default.
- **Benefit:** Extensibility without cannibalizing the Pro settings UI.
- **Priority:** Medium · **Complexity:** Small · **Milestone:** 1.4.0
- **Status:** Proposed · **Source:** New Suggestion

### Translation readiness (.pot + language files)
- **Description:** Ship a `.pot` file in `languages/` and verify text-domain loading.
- **Benefit:** Enables community translations.
- **Priority:** Low · **Complexity:** Small · **Milestone:** 1.4.0
- **Status:** Proposed · **Source:** New Suggestion

### CI compatibility matrix
- **Description:** Extend GitHub Actions to run Plugin Check against multiple WordPress/WooCommerce versions.
- **Benefit:** Catches compatibility regressions before release.
- **Priority:** Medium · **Complexity:** Medium · **Milestone:** 1.4.0
- **Status:** Proposed · **Source:** New Suggestion

---

## Phase 4 — Long-Term Development

### Block-based WooCommerce email coverage
- **Description:** As WooCommerce moves toward block-based emails, verify and extend replacement coverage for that rendering path.
- **Benefit:** Keeps "Delivery" consistent as WooCommerce evolves.
- **Priority:** Medium · **Complexity:** Large · **Milestone:** 2.0.0
- **Status:** Proposed · **Source:** New Suggestion

### Multisite handling
- **Description:** Verify and document network-activation behavior across a multisite network.
- **Benefit:** Supports agencies running many sites.
- **Priority:** Low · **Complexity:** Medium · **Milestone:** 2.0.0
- **Status:** Proposed · **Source:** New Suggestion

### Pro upgrade funnel refinement
- **Description:** Measure and refine the free→Pro path to the Shipping Text Manager without harming the free UX.
- **Benefit:** Sustainable monetization of the free listing.
- **Priority:** Low · **Complexity:** Small · **Milestone:** 2.0.0
- **Status:** Proposed · **Source:** New Suggestion
