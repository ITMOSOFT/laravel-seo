<?php

namespace ItmoSoft\Seo;

use Illuminate\Support\HtmlString;
use InvalidArgumentException;
use JsonException;

class SeoManager
{
    private array $data;

    /** @var array<int, array<string, mixed>> */
    private array $structuredData = [];

    public function __construct(array $defaults = [])
    {
        $this->data = $defaults;
    }

    public function title(string $title): self
    {
        $this->data['title'] = $title;
        return $this;
    }

    public function description(?string $description): self
    {
        $this->data['description'] = $description;
        return $this;
    }

    public function canonical(?string $url): self
    {
        $this->data['canonical'] = $url;
        return $this;
    }

    public function robots(string $directives): self
    {
        $this->data['robots'] = $directives;
        return $this;
    }

    public function image(?string $url): self
    {
        $this->data['image'] = $url;
        return $this;
    }

    public function type(string $type): self
    {
        $this->data['type'] = $type;
        return $this;
    }

    public function siteName(string $name): self
    {
        $this->data['site_name'] = $name;
        return $this;
    }

    public function locale(string $locale): self
    {
        $this->data['locale'] = $locale;
        return $this;
    }

    public function twitterCard(string $card): self
    {
        $this->data['twitter_card'] = $card;
        return $this;
    }

    /**
     * Add a JSON-LD object. Pass an associative array describing one schema object.
     *
     * @param array<string, mixed> $schema
     */
    public function structuredData(array $schema): self
    {
        if (! isset($schema['@type'])) {
            throw new InvalidArgumentException('Structured data must include an @type key.');
        }

        $this->structuredData[] = $schema;
        return $this;
    }

    /**
     * @throws JsonException
     */
    public function render(): HtmlString
    {
        $tags = [];
        $title = $this->data['title'] ?? null;

        if (is_string($title) && $title !== '') {
            $tags[] = '<title>'.e($title).'</title>';
            $tags[] = '<meta property="og:title" content="'.e($title).'">';
            $tags[] = '<meta name="twitter:title" content="'.e($title).'">';
        }

        $this->appendMeta($tags, 'name', 'description', $this->data['description'] ?? null);
        $this->appendMeta($tags, 'name', 'robots', $this->data['robots'] ?? null);
        $this->appendMeta($tags, 'property', 'og:description', $this->data['description'] ?? null);
        $this->appendMeta($tags, 'name', 'twitter:description', $this->data['description'] ?? null);

        $this->appendMeta($tags, 'property', 'og:type', $this->data['type'] ?? null);
        $this->appendMeta($tags, 'property', 'og:site_name', $this->data['site_name'] ?? null);
        $this->appendMeta($tags, 'property', 'og:locale', $this->data['locale'] ?? null);
        $this->appendMeta($tags, 'property', 'og:image', $this->data['image'] ?? null);
        $this->appendMeta($tags, 'name', 'twitter:card', $this->data['twitter_card'] ?? null);
        $this->appendMeta($tags, 'name', 'twitter:image', $this->data['image'] ?? null);

        $canonical = $this->data['canonical'] ?? null;
        if (is_string($canonical) && $canonical !== '') {
            $tags[] = '<link rel="canonical" href="'.e($canonical).'">';
            $tags[] = '<meta property="og:url" content="'.e($canonical).'">';
        }

        foreach ($this->structuredData as $schema) {
            $json = json_encode(
                $schema,
                JSON_UNESCAPED_SLASHES
                    | JSON_UNESCAPED_UNICODE
                    | JSON_HEX_TAG
                    | JSON_HEX_APOS
                    | JSON_HEX_AMP
                    | JSON_HEX_QUOT
                    | JSON_THROW_ON_ERROR
            );
            $tags[] = '<script type="application/ld+json">'.$json.'</script>';
        }

        return new HtmlString(implode("\n", $tags));
    }

    /**
     * @param array<int, string> $tags
     */
    private function appendMeta(array &$tags, string $attribute, string $name, mixed $value): void
    {
        if (is_string($value) && $value !== '') {
            $tags[] = '<meta '.$attribute.'="'.e($name).'" content="'.e($value).'">';
        }
    }
}
