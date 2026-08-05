# Developer Documentation

## Requirements

- Node.js 20+ (see `.nvmrc`)
- PHP 8.2+
- Composer

## Testing archiving:

`git archive -o bluecadet_ajax_content.tar HEAD`

## npm commands

`npm run build` - Runs a build of css and js files.
`npm run watch` - Watches configured files for changes.
`npm run clean` - Deletes all files from dist folders.
`npx set-version -v 1.0.0-rc.1 -c` - Sets all the modules to a specific version number and tags the commit.

## Running Tests Locally

### PHPUnit Tests

To run PHPUnit tests locally, you need a working Drupal installation:

```bash
# From Drupal root
vendor/bin/phpunit --bootstrap core/tests/bootstrap.php \
  -c modules/bluecadet/bluecadet_ajax_content/phpunit.xml \
  modules/bluecadet/bluecadet_ajax_content
```

### Coding Standards

```bash
# From Drupal root
vendor/bin/phpcs --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt \
  modules/bluecadet/bluecadet_ajax_content

vendor/bin/phpcs --standard=DrupalPractice --extensions=php,module,inc,install,test,profile,theme,css,info,txt \
  modules/bluecadet/bluecadet_ajax_content
```

### Deprecation Checking

```bash
# From Drupal root
vendor/bin/drupal-check modules/bluecadet/bluecadet_ajax_content
```

## Version Compatibility

| Module Version | Drupal Version | PHP Version |
|----------------|----------------|-------------|
| 1.1.x          | 10.5+, 11.2+   | 8.2+        |
| 1.0.x          | 10.x           | 7.4+        |

## CI/CD

GitHub Actions runs tests on:
- Drupal 10.5.x, 10.6.x (PHP 8.2, 8.3)
- Drupal 11.2.x, 11.3.x (PHP 8.3)
- MariaDB 10.6, 11.4
