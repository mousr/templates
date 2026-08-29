<?php declare(strict_types=1);

namespace Mousr\Templates;

use Mousr\Templates\Exception\InvalidTemplateRoot;
use Mousr\Templates\Exception\TemplateNotFound;

readonly class Renderer {
    private string $templateRoot;

    /** @throws InvalidTemplateRoot */
    public function __construct(string $templateRoot, private array $globalContext) {
        $realPath = realpath($templateRoot);
        if ($realPath === false) {
            throw new InvalidTemplateRoot($templateRoot);
        }

        $this->templateRoot = rtrim($realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /** @throws TemplateNotFound */
    public function render(string $path, array $context): void {
        if (($realPath = realpath($path)) === false
            || is_file($realPath) === false
            || str_starts_with($realPath, $this->templateRoot) === false) {
            throw new TemplateNotFound($path);
        }

        extract($context, EXTR_SKIP);
        extract($this->globalContext, EXTR_SKIP);
        extract(['renderer' => $this], EXTR_SKIP);
        require $realPath;
    }

    /** @throws TemplateNotFound */
    public function toString(string $path, array $context): string {
        ob_start();
        $this->render($path, $context);
        return ob_get_clean();
    }
}
