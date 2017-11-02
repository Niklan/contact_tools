# Contact Tools

This module is pack of tools for working with Drupal 8 core Contact module forms. It's provide:

- AJAX support for contact forms on demand.
- Service to easy call contact form w/ and w/o AJAX support, generate link which opens form in modal window w/ and w/o AJAX.
- Text filter which allows to create simple links with modal form support.
- Twig functions to easy embed modal links or whole form in the template.
- Hooks to modify data on every step.

There is no UI for module. I don't see any reason for creating it right now. This tools are targeted for developers to use on demand, not globally.

## Installation

It's recommended to install module via composer.

```bash
composer require drupal/contact_tools
```

## Documentation

For more information look at documentation which is provided with module in `/docs` folder:

- [Drupal service](docs/service.md)
- [Text filter](docs/filter.md)
- [Twig functions](docs/twig.md)
- [Hooks](docs/hooks.md)
