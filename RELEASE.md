# Release checklist

1. Run the full test suite locally.
2. Confirm the GitHub Actions matrix passes for supported PHP and Laravel versions.
3. Commit and push the package source to `main`.
4. Create and push the annotated `v1.0.0` tag.
5. Create the GitHub Release using the title and notes below.
6. Submit `itmosoft/laravel-seo` to Packagist or confirm the Packagist GitHub hook is active.

## Git commands (run locally)

```bash
git add .
git status
git commit -m "feat: add Laravel SEO package"
git push origin main

git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```

## GitHub Release

Title: `v1.0.0 - Initial Release`

```markdown
## Features
- Fluent SEO metadata manager with configurable defaults.
- Page title and meta description rendering.
- Canonical URL and robots directives.
- Open Graph and Twitter Card metadata.
- JSON-LD structured data support.
- Blade `@seo` directive.
- Scoped service registration for request lifecycle isolation.
- Escaped metadata values and safely encoded JSON-LD.

## Compatibility
- PHP 8.3+
- Laravel 12 and 13

## Installation
composer require itmosoft/laravel-seo
```

## Packagist

Package name: `itmosoft/laravel-seo`

After the GitHub tag is published, verify Packagist detects `v1.0.0`. If this is the first release, submit the GitHub repository URL at https://packagist.org/packages/submit.
