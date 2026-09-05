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

With Blade or Twig, static analysis is an afterthought. With native PHP templates, static analysis just works. No more custom parsing of our templates to check for issues, no need for extra extensions in your IDE. All your native PHP tools just work out of the box!

## File extensions

You're free to choose any file extension you want, but it's recommended to end with `.{filetype}.php` to make sure all your tools pick them up as PHP files. We'll use `.html.php` for HTML templates, and `.xml.php` for XML templates in this README.

## Writing a template

To make sure our templates are in strict mode and that your IDE recognizes all variables, it's recommended to start your templates with the following section: 

```php
<?php declare(strict_types=1);
\Mousr\Templates\Assert::template($renderer, $escaper, $context, BaseLayout::class);
?>
<html lang="en"></html>
```

This does several things: It ensures that the `$renderer` and `$escaper` variables are present when rendering Mousr templates, and of the correct type. The fourth parameter is a class-string of the View Context class you create. If you set it to a class-string, it will ensure that the variable `$context` is available and of the correct type. That in turn results in your IDE and PHPStan understanding that these variables exist and of what type they are.

## Rendering a template

Templates are rendered by calling the `render` method, which directly outputs:

```php
(new \Mousr\Templates\Renderer(__DIR__))
    ->render(__DIR__ . '/base-layout.html.php', new BaseLayout('bar'));
```

If you want to retrieve the rendered template as a string instead of directly outputting you can use the `toString` method:

```php
$html = (new \Mousr\Templates\Renderer(__DIR__))
    ->toString(__DIR__ . '/base-layout.html.php', new BaseLayout('bar'));
```

For both the `render` and the `toString` method, the 2nd parameter `$context` is passed in most cases but can be omitted if your template doesn't require a ViewClass.

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

These objects are passed when rendering a template.

## Escaping

There's no auto escaping in this library. This is intentional. Auto-escaping gives a false sense of security. Escaping in HTML is highly dependent on context, and should happen differently in attributes, JavaScript, CSS and inline. By making escaping explicit, we can ensure we always escape for the right context.

```php
<a href="<?= $escaper->url($context->url) ?>">url</a>
<a href="http://example.com"><?= $escaper->inline($context->linkText) ?></a>
```

Make sure you use the mousr/templates-phpstan-extension to check that all the necessary escaping is there!

## Reusing templates

There are two types of reusable templates: Partials (also called includes or fragments) and layouts (inheritance).

### Partials

Using partials is really simple with this library. You can call the `toString` method on the `$renderer` class to render a partial template:

```php
<?= $renderer->toString(__DIR__ . '/menu.html.php', $context->menu) ?>
```

### Layouts

Layouts are currently not implemented.
