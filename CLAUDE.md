# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Drupal contrib-style module (`bluecadet_ajax_content`) that provides a JS library for lazy/deferred AJAX content loading, driven entirely by `data-*` attributes on markup — there is no admin UI or config entity. The module itself does nothing on its own; `bluecadet_ajax_content_example` is a submodule with controllers/routes that demonstrate the three loading patterns and back the functional JS tests.

Requires Drupal 10.5+/11.2+ and PHP 8.2+. This is a library consumed by other Drupal sites via Composer (package type `custom-drupal-module`), so it does not run standalone — testing and running require a full Drupal installation (see below).

## Architecture

- `assets/src/js/content-ajaxing.js` — the entire behavior, as a single `Drupal.behaviors.customAjaxContent`. Compiled to `assets/dist/js/content-ajaxing.js` via bldr; edit the `src` file, never `dist` directly. Three independent trigger mechanisms, selected purely by which `data-*` attribute is present on an element:
  - `data-ajax-scroll="<url>"` — fetches the URL and swaps `innerHTML` when the element intersects the viewport (one shared `IntersectionObserver`), then calls `Drupal.attachBehaviors` and optionally dispatches a custom event named by `data-ajax-event`.
  - `data-ajax-commands="<url>"` — same scroll-triggered observer, but instead invokes `Drupal.ajax({url, httpMethod: 'GET'}).execute()`, letting the response drive Drupal AJAX commands (e.g. `ReplaceCommand`) server-side.
  - `data-ajax-trigger="<url>"` — no observer; listens for a custom `trigger-ajax` event on the element and fires `Drupal.ajax(...)` when it happens.
  - `data-ajax-now="<url>"` — loads immediately on attach (not observer-based), batching multiple elements that share the same URL into a single fetch.
  - Elements get an `.observing` class once handled so re-running `attach` (e.g. after `Drupal.attachBehaviors`) doesn't double-register them.
- `bluecadet_ajax_content.module` — unrelated to the JS behavior; implements `hook_update_status_alter()` using `Bluecadet\DrupalPackageManager\Checker` (from the `bluecadet/bc_drupal_package_manager` package) to annotate this module's update status. Covered by `tests/src/Kernel/UpdateStatusAlterTest.php`.
- `bluecadet_ajax_content_example/` — a real submodule (own `.info.yml`/`.libraries.yml`/routing) whose controllers (`SimpleExample`, `ScrollExample`, `AjaxCommandsExample`) each expose a `/ajax-content/*` page and a matching `/ajax-api/*` AJAX endpoint, one per loading pattern above. `AjaxCommandsExample` also demonstrates attaching a library (CSS) via the AJAX response's `#attached`. These routes are what `tests/src/FunctionalJavascript/AjaxContentTest.php` drives with a real browser (WebDriver/Selenium) to assert the JS behavior end-to-end.

## Build (JS/CSS)

Uses `@bluecadet/bldr` (internal Bluecadet build tool) and Node 20+ (`.nvmrc`).

```bash
npm run build   # compiles assets/src -> assets/dist (JS/CSS) via bldr
npm run watch   # watch mode
npm run clean   # remove all dist output
```

There is no JS test suite (`npm test` is a no-op placeholder).

## PHP testing and standards

This module cannot be tested in isolation — it must live inside a Drupal installation at `modules/bluecadet/bluecadet_ajax_content`. Run all commands below from the Drupal root, not this repo's root.

```bash
# PHPUnit — all suites (unit/kernel/functional/functional-javascript per phpunit.xml)
vendor/bin/phpunit --bootstrap core/tests/bootstrap.php \
  -c modules/bluecadet/bluecadet_ajax_content/phpunit.xml \
  modules/bluecadet/bluecadet_ajax_content

# Single test file
vendor/bin/phpunit --bootstrap core/tests/bootstrap.php \
  -c modules/bluecadet/bluecadet_ajax_content/phpunit.xml \
  modules/bluecadet/bluecadet_ajax_content/tests/src/FunctionalJavascript/AjaxContentTest.php

# Coding standards (Drupal + DrupalPractice)
vendor/bin/phpcs --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt \
  modules/bluecadet/bluecadet_ajax_content
vendor/bin/phpcs --standard=DrupalPractice --extensions=php,module,inc,install,test,profile,theme,css,info,txt \
  modules/bluecadet/bluecadet_ajax_content

# Deprecation/compatibility check
vendor/bin/drupal-check modules/bluecadet/bluecadet_ajax_content
```

`FunctionalJavascript` tests require a running WebDriver/Selenium instance (see `phpunit.xml`'s `MINK_DRIVER_ARGS_WEBDRIVER`) and a running Drupal site (`SIMPLETEST_BASE_URL`) — they are the primary regression coverage for `content-ajaxing.js` and exercise it through real browser scroll/intersection events, not mocks.

`phpstan.neon.dist` runs at level 2 with `mglaman/phpstan-drupal` conventions; it's installed and invoked only in CI (not part of the local commands above since it needs Drupal core context).

## CI

`.github/workflows/drupal-tests-and-standards.yml` calls the reusable `.github/workflows/drupal-test-runner.yml`, which clones Drupal core fresh, symlinks this module in via a path repository, then runs PHPCS, PHPStan, drupal-check, and PHPUnit. It runs on push/PR to `1.x`, monthly on a schedule, and via manual `workflow_dispatch` (which lets you target a specific Drupal core branch, PHP version, MariaDB version, and PHPUnit path). PR builds test a reduced matrix (10.6.x/11.3.x); push/schedule test the full matrix (10.5.x–11.3.x).

## Versioning

Both `bluecadet_ajax_content.info.yml` and `package.json` carry the module version and must stay in sync. Use `npx set-version -v <version> -c` to bump and tag both at once rather than editing them by hand.

## Keeping this file current

When you make a change that affects the Architecture section above — a new loading pattern/data-attribute, a new hook implementation, a new submodule/controller, or a change to the test or CI structure — update that section in the same change. Don't let this file drift from the code it describes.
