<?php declare(strict_types=1);

namespace Mousr\Templates;

use Mousr\Templates\Exception\InvalidTemplateRoot;
use Mousr\Templates\Exception\TemplateNotFound;

final readonly class Renderer {
    private string $templateRoot;

    /** @throws InvalidTemplateRoot */
    public function __construct(string $templateRoot, private string $encoding = 'utf-8') {
        $realPath = realpath($templateRoot);
        if ($realPath === false) {
            throw new InvalidTemplateRoot($templateRoot);
        }

        $this->templateRoot = rtrim($realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /** @throws TemplateNotFound */
    public function render(string $path, ViewContext $context): void {
        if (($realPath = realpath($path)) === false
            || is_file($realPath) === false
            || str_starts_with($realPath, $this->templateRoot) === false) {
            throw new TemplateNotFound($path);
        }

        extract([
            'context' => $context,
            'renderer' => $this,
            'escaper' => new Escaper($this->encoding),
            'encoding' => $this->encoding,
        ], EXTR_SKIP);
        require $realPath;
    }

    /** @throws TemplateNotFound */
    public function toString(string $path, ViewContext $context): string {
        ob_start();
        $this->render($path, $context);
        return ob_get_clean();
    }
}
