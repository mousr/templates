<?php declare(strict_types=1);

namespace Mousr\Templates;

use JsonException;

final readonly class Escaper {
    public function __construct(
        public string $encoding,
    ) {}

    public function inline(string $string): string {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $this->encoding);
    }

    public function attr(string $string): string {
        return $this->inline($string);
    }

    public function url(string $url): ?string {
        if (in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https', ''], true) === false) {
            return null;
        }

        return $this->inline($url);
    }

    /** @throws JsonException */
    public function js(string $js): string {
        return json_encode($js, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR);
    }
}
