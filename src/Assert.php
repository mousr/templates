<?php declare(strict_types=1);

namespace Mousr\Templates;

use Mousr\Templates\Exception\InvalidType;

final readonly class Assert {
    /**
     * This function asserts that your template is called with the correct variables in the global scope.
     * It uses pass-by-reference so you don't have to check isset($variable) first, as pass-by-reference
     * creates a variable as null when it doesn't exist. This will then immediately fail validation, so
     * means we can use a shorter function call syntax across all our templates.
     *
     * @template T of ViewContext
     * @param class-string<T>|null $contextType
     * @throws InvalidType
     *
     * @param-out Renderer $renderer
     * @param-out Escaper $escaper
     * @param-out ($contextType is null ? null : T) $context
     */
    public static function template(
        ?Renderer    &$renderer,
        ?Escaper     &$escaper,
        ?ViewContext &$context,
        ?string      $contextType,
    ): void {
        $renderer instanceof Renderer || throw new InvalidType('renderer', $renderer, Renderer::class);
        $escaper instanceof Escaper || throw new InvalidType('escaper', $escaper, Escaper::class);
        if ($contextType === null) {
            $context === null || throw new InvalidType('context', $context, null);
        } else {
            $context instanceof $contextType || throw new InvalidType('context', $context, $contextType);
        }
    }
}
