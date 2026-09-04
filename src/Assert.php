<?php declare(strict_types=1);

namespace Mousr\Templates;

final readonly class Assert {
    /**
     * This function asserts that your template is called with the correct variables in the global scope.
     * It uses pass-by-reference so you don't have to check isset($variable) first, as pass-by-reference
     * creates a variable as null when it doesn't exist. This will then immediately fail validation, so
     * means we can use a shorter function call syntax across all our templates.
     *
     * @template T of \Mousr\Templates\ViewContext
     * @param class-string<T> $contextType
     *
     * @phpstan-assert \Mousr\Templates\Renderer $renderer
     * @phpstan-assert \Mousr\Templates\Escaper $escaper
     * @phpstan-assert T $context
     */
    public static function template(
        ?\Mousr\Templates\Renderer &$renderer,
        ?\Mousr\Templates\Escaper &$escaper,
        ?\Mousr\Templates\ViewContext &$context,
        string $contextType,
    ): void {
        $renderer instanceof \Mousr\Templates\Renderer || throw new \Mousr\Templates\Exception\InvalidType($renderer);
        $escaper instanceof \Mousr\Templates\Escaper || throw new \Mousr\Templates\Exception\InvalidType($escaper);
        $context instanceof $contextType || throw new \Mousr\Templates\Exception\InvalidType($context);
    }
}
