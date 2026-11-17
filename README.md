# ITMOSOFT Laravel SEO

SEO metadata, canonical URLs, social sharing tags, and JSON-LD structured data for Laravel applications.

## Requirements

- PHP 8.3+
- Laravel 12 or 13

## Installation

```bash
composer require itmosoft/laravel-seo
php artisan vendor:publish --tag=itmosoft-seo-config
```

Laravel package auto-discovery registers the service provider.

## Basic usage

Resolve the scoped manager, configure page metadata, and render it in your layout:

```php
use ItmoSoft\Seo\SeoManager;

$seo = app(SeoManager::class);

$seo->title('Product name')
    ->description('A concise product description.')
    ->canonical(url()->current())
    ->image(asset('images/product.png'))
    ->type('product');
```

In your Blade layout, inside `<head>`:

```blade
{!! app(\ItmoSoft\Seo\SeoManager::class)->render() !!}
```

Or use the package directive:

```blade
@seo
```

## JSON-LD structured data

```php
app(SeoManager::class)->structuredData([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->name,
    'description' => $product->description,
]);
```

Add multiple structured-data objects by calling `structuredData()` more than once.

## Available methods

- `title(string $title)`
- `description(?string $description)`
- `canonical(?string $url)`
- `robots(string $directives)`
- `image(?string $url)`
- `type(string $type)`
- `siteName(string $name)`
- `locale(string $locale)`
- `twitterCard(string $card)`
- `structuredData(array $schema)`
- `render(): HtmlString`

Methods are fluent and return the same manager instance.

## Configuration

Publish `config/itmosoft-seo.php`. Defaults include title, description, canonical URL, robots directives, social image, Open Graph type/site name/locale, and Twitter card type.

The manager is registered as a Laravel scoped service so page metadata is not shared across normal request lifecycles in long-running application servers.

## Security

Metadata values are HTML-escaped. JSON-LD is encoded with JSON hex flags to prevent script-tag injection. Only pass trusted, application-generated schema structures.

## Scope boundaries

This package renders metadata; it does not generate XML sitemaps, crawl websites, perform keyword research, analyze rankings, manage redirects, or guarantee search-engine indexing.

## Tests

```bash
composer install
composer test
```

## License

MIT. See [LICENSE](LICENSE).
