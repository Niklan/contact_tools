# Contact Tools

Module for Drupal 8 which provide some helpers to work with contact module forms.

## Call service

All provided tools is now accessible via service `contact_tools`.

```php
$contact_tools = \Drupal::service('contact_tools');
```

After that you can call all methods.

### createModalLink($link_title, $contact_form, $url_options = [], $modal_options = [])

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
return $contact_tools::createModalLink('Call me', 'callback');
```

#### Example 2

Pass some values to the form via query.

```php
$contact_tools = \Drupal::service('contact_tools');
return $contact_tools::createModalLink('Call me', 'callback', ['query' => ['product' => $node->id()]]);
```

## Contact tools filter

### modalLink

```html
[contact]{"type": "modalLink", "link_title": "Call me", "contact_form": "callback"}[/contact]
```

### modalLinkAjax

```html
[contact]{"type": "modalLinkAjax", "link_title": "Call me", "contact_form": "callback"}[/contact]
```