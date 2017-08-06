# Contact Tools

Module for Drupal 8 which provide some helpers to work with contact module forms.

## Service

All provided tools is accessible via service `contact_tools`.

```php
$contact_tools = \Drupal::service('contact_tools');
```

After that you can call all methods.

### createModalLinkAjax() / createModalLink($link_title, $contact_form, $link_options = [], $url_options = [])

The main difference of those two method, that one of it load form with AJAX, another
load just form in modal. They have same arguments.

- `$link_options`: there is most usable data. Here you can pass attributes for link,
additional query parameters and modal data attributes with settings.

Return renderable array type 'link' which will open contact form in Modal window.

By default `$link_options` provide those settings:

```php
$link_options_defaults = [
  'attributes' => [
    'class' => ['use-ajax'],
    'data-dialog-type' => 'modal',
    'data-dialog-options' => Json::encode([
      'width' => 'auto',
    ]),
  ],
];
```

You can modify it by provided additional settings and\or replace default ones.


#### Example 1

```php
$contact_tools = \Drupal::service('contact_tools');
return $contact_tools::createModalLinkAjax('Call me', 'callback');
```

#### Example 2

Pass some values to the form via query.

```php
$contact_tools = \Drupal::service('contact_tools');
return $contact_tools::createModalLinkAjax('Call me', 'callback', ['query' => ['product' => $node->id()]]);
```

#### Example 3

Get ajax form render array.

```php
$contact_tools = \Drupal::service('contact_tools');
return $contact_tools->getFormAjax('feedback');
```

## Filter

### modalLink

```html
[contact]{"type": "modalLink", "contact_form": "callback", "link_title": "Call me!", "link_options": {"attributes":{"class":["callback-link"]}}}[/contact]
```

### modalLinkAjax

```html
[contact]{"type": "modalLinkAjax", "contact_form": "callback", "link_title": "Call me!", "link_options": {"attributes":{"class":["callback-link"]}}}[/contact]
```

## Twig

Module also provides Twig functions as well!

### contact_form_ajax()

Load contact form with ajax support.

#### Example 1

```twig
{{ contact_form_ajax('feedback') }}
```

### contact_modal() and contact_modal_ajax()

As above, they are the same, just one with ajax.

#### Example 1

```twig
{{ contact_modal_ajax('Call me!', 'callback') }}
```

#### Example 2

```twig
{% set link_options = {
  query: {
    service: node.nid
  }
} %}
{{ contact_modal_ajax('Call me!', 'callback', link_options) }}
```
