# Changelog

## Changes in dev

- Improved code quality.
- Added ability to pass data to form for `getForm()`, `getFormAjax()` and their Twig functions. Also added example how to use it.

## 8.x-1.0-rc1

- Fix: TypeError: Argument 2 passed to HOOK_contact_tools_modal_link_options_alter() must be of the type array, null given.
- Replaced deprecated drupal messages functions with object.
- AJAX wrapper container now has suffix `-ajax-wrapper` for all inherited classes. It will make theming easier without class duplicates.
