<?php declare(strict_types=1);

namespace Mousr\Templates\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class PreEscapedHTML {}
