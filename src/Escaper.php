<?php declare(strict_types=1);

namespace Mousr\Templates;

use JsonException;
use Mousr\Templates\Exception\ValueError;

final readonly class Escaper {
    public function __construct(
        public string $encoding,
    ) {}

    /**
     * Escapes a value for output inside an HTML element's text content
     * For example, <p><?= $escaper->text($context->foo) ?></p>
     */
    public function text(string $string): string {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, $this->encoding);
    }

    /**
     * Escapes a value for output inside a quoted HTML attribute
     * :warning: Always use quoted attributes, never inject into unquoted attributes.
     * For example, <input value="<?= $escaper->attr($context->bar) ?>">
     */
    public function attr(string $string): string {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, $this->encoding);
    }

    /**
     * Escapes a URL for use in href/src/action attributes.
     * Only http, https, and relative URLs are allowed.
     * For example, <a href="<?= $escaper->url($context->url) ?>">
     *
     * @throws ValueError if the URL scheme is not allowed
     */
    public function url(string $url): string {
        $parsedUrl = parse_url($url);
        if ($parsedUrl === false) {
            throw new ValueError(sprintf('Malformed url "%s"', $url));
        }

        $scheme = isset($parsedUrl['scheme']) ? strtolower($parsedUrl['scheme']) : null;
        if (in_array($scheme, ['http', 'https', '', null], true) === false) {
            throw new ValueError(sprintf('URL scheme "%s" is not allowed', $scheme));
        }

        return $this->attr($url);
    }

    /**
     * Escapes a value as a JSON literal safe for inline use in a <script> block
     * Not safe to use inside an attribute.
     * For example, <script>var config = <?= $escaper->js($context->config) ?>;</script>
     *
     * @throws JsonException
     */
    public function js(mixed $js): string {
        return json_encode($js, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR);
    }
}
