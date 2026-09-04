<?php declare(strict_types=1);

namespace Mousr\Templates\Exception;

final class InvalidType extends TemplateException {
    public function __construct(string $variableName, mixed $variable, string $expectedType) {
        parent::__construct(
            sprintf(
                'Expected variable %s to be of type %s but got %s',
                $variableName,
                $expectedType,
                is_object($variable) ? $variable::class : gettype($variable),
            ),
        );
    }
}
