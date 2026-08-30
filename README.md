# templates

A strict and simple template engine for PHP

## Why this package?

With Blade or Twig, static analysis is an afterthought. With native PHP templates static analysis just works. No more running over an AST to detect issues, no need for extra extensions in your IDE, no custom-built static analysis tools for your templates. All your native PHP tools just work out of the box!

## File extensions

You're free to choose any file extension you want, but it's recommended to end with `.{filetype}.php` to make sure all your tools pick them up as PHP files. We'll use `.html.php` for HTML templates, and `.xml.php` for XML templates in this readme.

## Writing a template

To make sure our templates are in strict mode and that your IDE recognizes all variables, it's recommended to start your templates with the following section: 

```php
<?php declare(strict_types=1);
assert(isset($encoding) && is_string($encoding));
assert(isset($renderer) && $renderer instanceof \Mousr\Templates\Renderer);
assert(isset($escaper) && $escaper instanceof \Mousr\Templates\Escaper);
assert(isset($context) && $context instanceof \FQN\Of\Your\ViewModel);
?>
<html lang="en"></html>
```

## The template Context

Each template has a `ViewContext`. These are predefined object structures tagged with the `ViewContext` interface. For example:

```php
final readonly class BaseLayout implements ViewContext {
    public function __construct(
        public string $bar,
    ) {}
}
```

These objects are passed when rendering a template:

```php
(new \Mousr\Templates\Renderer(__DIR__))
    ->render(__DIR__ . '/base-layout.html.php', new BaseLayout('bar'));
```

To get autocomplete and static analysis support in these templates, one can then add the type assertion at the top of the template for the specific view class that's expected:

```php
<?php
/** */
assert(isset($context) && $context instanceof \BaseLayout);
/**  */
?>
```

## Escaping

As there is no transpiling to HTML and you'll be writing HTML directly, it does mean that you'll also need to do escaping yourself. All templates get an instance of `Escaper` available in their global scope, as variable `$escaper`. There are several methods available for several contexts: `->inline()`, `->attr()`, `->url()` and `->js()`. Let's start with a simple example for the main template, we'll need to set our charset for our HTML:

```php
<?php declare(strict_types=1);
assert(isset($encoding) && is_string($encoding));
assert(isset($renderer) && $renderer instanceof \Mousr\Templates\Renderer);
assert(isset($escaper) && $escaper instanceof \Mousr\Templates\Escaper);
assert(isset($context) && $context instanceof \FQN\Of\Your\ViewModel);
?>
<html lang="en">
    <head>
        <meta charset="<?= $escaper->attr($encoding) ?>">
    </head>
    <body>
    </body>
</html>
```

Please note that the charset is needed for escaping as well. If you want to use a different charset, you can override it when constructing the `Renderer` class.
