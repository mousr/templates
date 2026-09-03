# mousr/templates

A strict and simple template engine for PHP

> This package should not be used without its corresponding PHPStan extension in a merge-blocking pipeline

To get started, run:

```bash
composer require mousr/templates
```

And to also install the corresponding PHPStan extension, run:
```bash
composer require mousr/templates-phpstan-extension --dev
```

## Why this package?

With Blade or Twig, static analysis is an afterthought. With native PHP templates static analysis just works. No more running over an AST to detect issues, no need for extra extensions in your IDE, no custom-built static analysis tools for your templates. All your native PHP tools just work out of the box!

## File extensions

You're free to choose any file extension you want, but it's recommended to end with `.{filetype}.php` to make sure all your tools pick them up as PHP files. We'll use `.html.php` for HTML templates, and `.xml.php` for XML templates in this readme.

## Writing a template

To make sure our templates are in strict mode and that your IDE recognizes all variables, it's recommended to start your templates with the following section: 

```php
<?php declare(strict_types=1);
\Mousr\Templates\Assert::template($encoding, $renderer, $escaper, $context, BaseLayout::class);
?>
<html lang="en"></html>
```

This does several things: It makes sure that all the variables should always be present when rendering Mousr templates are actually present, and of the correct type. That in turn results in your IDE and PHPStan understanding that these variables exist and of what type they are.

## The template Context

Each template has a `ViewContext`;

```php
final readonly class BaseLayout implements ViewContext {
    public function __construct(
        public string $bar,
    ) {}
}
```

You're free to use any type of PHP class here, as long as you implement the `ViewContext` interface. This interface doesn't enforce any methods, and just exists to tag the classes that can be used as ViewContext objects.

These objects are passed when rendering a template:

```php
(new \Mousr\Templates\Renderer(__DIR__))
    ->render(__DIR__ . '/base-layout.html.php', new BaseLayout('bar'));
```

To get autocomplete and static analysis support in these templates, you should make sure you specify the specific view class you expect as the fifth argument for the template method.

## Escaping

There's no auto escaping in this library. This is intentional. Auto-escaping gives a false sense of security. Escaping in HTML is highly dependent on context, and should happen differently in attributes, JavaScript, CSS and inline. By making escaping explicit, we can ensure we always escape for the right context.

```php
<a href="<?= $escaper->url($context->url) ?>">url</a>
<a href="http://example.com"><?= $escaper->inline($context->linkText) ?></a>
```

Make sure you use the mousr/templates-phpstan-extension to check that all the necessary escaping is there!
