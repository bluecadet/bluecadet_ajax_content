# Custom Ajax Content

A Drupal module that provides a library for handling AJAX content loading utilizing Drupal's AJAX system.

## Requirements

- Drupal 10.5+ or Drupal 11.2+
- PHP 8.2 or higher

## Versions

### 1.x Branch

- **1.1.x**: Drupal 10.5+/11.2+ support (PHP 8.2+)
- **1.0.x**: Drupal 10.x support (PHP 7.4+)

## Includes

### Submodules

- bluecadet_ajax_content_example: Example controllers demonstrating the three AJAX content loading patterns

## Not using Composer

If you are not using composer, you can delete all unneeded files.

- composer.json

## Using Composer

If you are using composer to manage Drupal modules, make sure you add custom
location for this module to be downloaded to. You must add the installer types
line as well as the location for the module.

```json
  ...
  "installer-types": ["custom-drupal-module"],
  "installer-paths": {
    "web/core": ["type:drupal-core"],
    "web/modules/contrib/{$name}": ["type:drupal-module"],
    "web/modules/custom/{$name}": ["type:custom-drupal-module"],
    "web/profiles/contrib/{$name}": ["type:drupal-profile"],
    "web/themes/contrib/{$name}": ["type:drupal-theme"],
    "drush/contrib/{$name}": ["type:drupal-drush"]
  },
  ...
```

## Testing

This module includes automated tests that run via GitHub Actions against Drupal 10.5.x-10.6.x and 11.2.x-11.3.x (PHP 8.2-8.4, MariaDB 10.4/10.6) -- see `.github/drupal-ci.yml` for the exact matrix.

### Test Plan

#### Automated Tests (GitHub Actions)

The CI pipeline runs the following for each Drupal version:

1. **PHPCS** - Drupal coding standards validation
2. **DrupalPractice** - Best practices validation
3. **PHPStan** - Drupal-aware static analysis
4. **PHPUnit Functional JavaScript Tests** - Tests AJAX content loading

#### Current coverage

No dedicated unit/kernel tests exist yet -- the only automated test is a Functional JavaScript test exercising the three AJAX loading patterns end to end. Both the parent module's `.module` file and `bluecadet_ajax_content_example`'s controller classes are measured by the coverage report, but neither has real line coverage from that single functional test.

#### Manual Testing Checklist

For comprehensive validation, manually test the following:

1. **Simple/Immediate AJAX Load** (`/ajax-content/simple-example`)
   - Verify content replaces placeholder on page load
   - Confirm no JavaScript errors in console

2. **Scroll-triggered AJAX Load** (`/ajax-content/scroll-example`)
   - Verify content loads when scrolling into view
   - Test IntersectionObserver functionality

3. **AJAX Commands Load** (`/ajax-content/ajax-commands-example`)
   - Verify Drupal AJAX commands execute correctly
   - Confirm CSS/JS attachments load

4. **Custom Event Triggers**
   - Test `data-ajax-trigger` attribute functionality
   - Verify custom events dispatch correctly

## Changelog

### 1.1.x

- Added Drupal 11 compatibility
- Updated minimum PHP version to 8.2
- Updated PHPUnit configuration for PHPUnit 10+
- Updated GitHub Actions workflow for modern Drupal versions
- Fixed test namespace and annotations

### 1.0.x

- Initial commit allowing for 3 different types of Content AJAXing
- Adding in another "trigger" type for Content AJAXing

<br>
<br>
<br>

## Proudly developed @ Bluecadet

<p style="background-color: white; padding: 20px">
  <a href="https://www.bluecadet.com/"><img style="max-width: 50%; min-width: 300px; background: white; padding: 20px;" src="https://www.bluecadet.com/wp-content/themes/bluecadet-2018/images/logo/logo-bluecadet-black.svg" alt="Bluecadet"></a>
</p>
