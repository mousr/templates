<?php declare(strict_types=1);

namespace Mousr\Templates\Exception;

final class InvalidType extends TemplateException {
    public function __construct(mixed &$variable) {
        parent::__construct(($type = gettype($variable)) === 'object' ? $variable::class : $type);
    }
}
