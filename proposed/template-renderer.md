Common Interface for Rendering Templates
========================================

This document describes a common interface for template renderers.

The goal set by `TemplateRendererInterface` is to standardize how frameworks, libraries and CMSs
render their templates so that projects are more free to use the template renderer they prefer.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The word `implementor` in this document is to be interpreted as someone
implementing the `TemplateRendererInterface` in a [template engine][] library.
Users of template renderer are referred to as `user`.
The word `enduser` in this document is to be interpreted as someone using
a library, framework, CMS created by a `user`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

Having common interfaces for rendering templates allows developers to create libraries that can interact with many frameworks and other libraries in a common fashion.

Some examples may use the Interface:

 - Content Management Systems (Sulu CMS, Drupal, Typo3, Contao CMS, ...)
 - E-Commerce Platforms (Sylius, Spryker)
 - Newsletter/Mail Libraries (Symfony Mailer, Abstractions over Mailchimp and other Newsletter tools) 
 - Anything following ["Separating content from presentation"](https://en.wikipedia.org/wiki/Separation_of_content_and_presentation) where the presentation is project specific.

Some examples may implement the Interface or providing a bridge:

 - Blade (Laravel Framework)
 - Laminas View
 - Latte (Nette Framework)
 - Smarty
 - Sulu CMS
 - Twig (Symfony Framework)
 - Typo3

## Definitions

* **Template** - A string representation of a template.
* **Context** - An array of context data given to the rendered template.
* **TemplateRenderer** - A renderer which will render the template with the given context and return the rendered content as string.

### Template

A template MUST be a string representation of a given template supported by the template renderer. It MAY be a file path
to the template file, but it can also be a virtual name or path supported only by a specific template renderer. The
template is not limited by specific characters by definition but a template renderer MAY support only specific one.

### Context

A context MUST be an array of the available variables given to the template renderer. The array keys represent the
variable names and MUST be strings. The array values represent the variable values and can be anything and
.

### TemplateRenderer

A template renderer is a service implementing the `TemplateRendererInterface`. It MUST be responsible of rendering a 
supported template given an OPTIONAL context. It MUST return the rendered content of the template as a string.
If the template was not found by the template renderer, an Exception implementing the `TemplateNotFoundExceptionInterface`
MUST be thrown. The template renderer SHALL NOT output/print/stream directly.

## Usage

While the implementor of `TemplateRendererInterface` MUST make sure that the template renderer behave like defined
above. The user of `TemplateRendererInterface` MUST allow when providing a library, application, cms for an enduser
using the Interface to give or configure the `template` used by the template renderer. This can be achieved with any
type of injection or configuration.

Basic implementor implementation:

```php
<?php

use Psr\TemplateRenderer\TemplateRendererInterface;

class TemplateRenderer implements TemplateRendererInterface
{
    public function render(string $template, array $context = []): string
    {
        switch ($template) {
            case 'home.tpl';

                return $this->renderTemplate('<p>{{name}}</p>', $context);
            default: 
                throw new TemplateNotFoundException($template);
        }
    }
    
    /**
     * @param array<string, mixed> $context
     * @return string
     */
    private function renderTemplate(string $content, array $context): string
    {
        foreach ($context as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $context[$key], $content);
        }
        
        return $content;
    }
}

use Psr\TemplateRenderer\TemplateNotFoundExceptionInterface;

class TemplateNotFoundException extends \RuntimeException implements TemplateNotFoundExceptionInterface
{
    public function __construct(private string $template, ?\Throwable $previous = null)
    {
        parent::__construct('Template not found: "' . $template . '"', 0 , $previous);
    }
    
    public function getTemplate(): string
    {
        return $this->template;
    }
}
```

Basic user implementation:

```php
<?php

use Psr\TemplateRenderer\TemplateRendererInterface;

class SomeController
{
    public function __construct(
        private TemplateRendererInterface $templateRenderer,
        private string $template,
    )

    public function someAction(): string
    {
        $context = [/* load something */]; 
    
        return $this->templateRenderer->render($this->template, $context);
    }
}
```

Example enduser implementation A:

```php
<?php

$controller = new Controller($twig, '@Context/pages/mail.html.twig');
$controller->someAction();
```

Example enduser implementation B:

```php
<?php

$controller = new Controller($blade, 'mail');
$controller->someAction();
```

Example enduser implementation C:

```php
<?php

$controller = new Controller($latte, 'mail.latte');
$controller->someAction();
```

## Interfaces

### TemplateRendererInterface

```php
<?php

namespace Psr\TemplateRenderer;

interface TemplateRendererInterface {
    /**
     * Render the template with the given context data.
     *
     * @param string $template
     * @param array<string, mixed> $context
     *
     * @return string
     */
    public function render(string $template, array $context = []): string;
}
```

### TemplateNotFoundExceptionInterface

```php
<?php

namespace Psr\TemplateRenderer;

use Throwable;

interface TemplateNotFoundExceptionInterface extends Throwable
{
    public function getTemplate(): string;
}
```
