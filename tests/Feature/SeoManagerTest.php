<?php

namespace ItmoSoft\Seo\Tests\Feature;

use ItmoSoft\Seo\SeoManager;
use ItmoSoft\Seo\Tests\TestCase;

class SeoManagerTest extends TestCase
{
    public function test_it_renders_default_title_and_robots_metadata(): void
    {
        config(['itmosoft-seo.defaults' => [
            'title' => 'Example Site',
            'robots' => 'index,follow',
        ]]);

        $html = app(SeoManager::class)->render()->toHtml();

        $this->assertStringContainsString('<title>Example Site</title>', $html);
        $this->assertStringContainsString('name="robots" content="index,follow"', $html);
    }

    public function test_it_renders_description_and_social_metadata(): void
    {
        $html = app(SeoManager::class)
            ->title('Product Page')
            ->description('A useful product.')
            ->image('https://example.com/product.png')
            ->type('product')
            ->render()
            ->toHtml();

        $this->assertStringContainsString('name="description" content="A useful product."', $html);
        $this->assertStringContainsString('property="og:type" content="product"', $html);
        $this->assertStringContainsString('name="twitter:image" content="https://example.com/product.png"', $html);
    }

    public function test_it_renders_canonical_and_open_graph_url(): void
    {
        $html = app(SeoManager::class)
            ->canonical('https://example.com/products/one')
            ->render()
            ->toHtml();

        $this->assertStringContainsString('<link rel="canonical" href="https://example.com/products/one">', $html);
        $this->assertStringContainsString('property="og:url"', $html);
    }

    public function test_it_escapes_html_in_metadata_values(): void
    {
        $html = app(SeoManager::class)
            ->title('<script>alert(1)</script>')
            ->description('" onload="alert(1)')
            ->render()
            ->toHtml();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('&quot; onload=&quot;', $html);
    }

    public function test_it_renders_json_ld_structured_data(): void
    {
        $html = app(SeoManager::class)
            ->structuredData([
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'Example Company',
            ])
            ->render()
            ->toHtml();

        $this->assertStringContainsString('type="application/ld+json"', $html);
        $this->assertStringContainsString('"@type":"Organization"', $html);
        $this->assertStringContainsString('Example Company', $html);
    }

    public function test_it_rejects_structured_data_without_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        app(SeoManager::class)->structuredData(['name' => 'Missing type']);
    }

    public function test_it_omits_optional_tags_when_values_are_null(): void
    {
        $html = (new SeoManager())->render()->toHtml();

        $this->assertSame('', $html);
    }

    public function test_it_applies_configured_defaults(): void
    {
        config(['itmosoft-seo.defaults' => [
            'title' => 'Configured',
            'description' => 'Default description',
            'site_name' => 'Configured Site',
            'locale' => 'en_GB',
            'twitter_card' => 'summary',
        ]]);

        $html = app(SeoManager::class)->render()->toHtml();

        $this->assertStringContainsString('<title>Configured</title>', $html);
        $this->assertStringContainsString('Default description', $html);
        $this->assertStringContainsString('og:site_name', $html);
        $this->assertStringContainsString('en_GB', $html);
        $this->assertStringContainsString('name="twitter:card" content="summary"', $html);
    }
}
