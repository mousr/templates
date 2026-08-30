<?php declare(strict_types=1);

namespace Mousr\Templates\Tests\Unit;

use Mousr\Templates\Exception\InvalidTemplateRoot;
use Mousr\Templates\Renderer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Renderer::class)]
class RendererTest extends TestCase {
    public function testConstructFailsOnInvalidTemplateRoot(): void {
        $this->expectExceptionMessageIs('foo/bar');
        $this->expectException(InvalidTemplateRoot::class);
        new Renderer('foo/bar');
    }
}
