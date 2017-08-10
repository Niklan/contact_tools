# Contact Tools

This module is pack of tools for working with Drupal 8 core Conatact module forms.

## Table of contents:

* [Service](#service)
  * [getForm(), getFormAjax()](#getForm)
  * [createModalLink(), createModalLinkAjax()](#createModalLink)
* [Filter](#filter)
* [Twig](#twig)

<a name="service"></a>

## Service

This module based on own service, that can done everything described below. The service
can help you when you work from PHP. To call servce just use:

```php
$contact_tools = \Drupal::service('contact_tools');
```

After that you can call all methods.

<a href="getForm"></a>

### getForm() and getFormAjax()

This two method generate `$form` render array with specified contact form and return it for your feture needs.

#### Parameters

- `$contact_form_id = 'default_form'`: (optional) contact form bundle name which you want to load. If none is pass, will be loaded default contact form which can be selected via contact admin settings.

#### Example

```php
$contact_tools = \Drupal::service('contact_tools');

// Just loading default form.
$default_form = $contact_tools->getForm();

// Load feedback form with AJAX submit handler.
$feedback_ajax = $contact_tools->getFormAjax('feedback');
```

<a href="createModalLink"></a>

### createModalLink() and createModalLinkAjax()

This methods generate '#link' which will load form in the modal on click. You can change modal settings and pass query parameters with link for future use in form alter.

#### Parameters

- `$link_title`: title of link. This variable is not translatable, if you need it, you must handle it by youself.
- `$contact_form`: the name of the contact which will be loaded in modal.
- `$link_options`: (optional) an array of options passed to link generation. For available options see `Url::fromUri()`. Here you can pass additional query parameters with link and link attributes such as class and data-dialog-option, which can be used to change jQuery ui dialog behavior. For more information about available dialo options see http://api.jqueryui.com/dialog/.
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
- `$url_option`: (optional) an array of options passed to Url generation for link above. For more details see `Url::fromRoute()`. **Can be purged, because at this moment is not useful at all.**
- `$key`: (option) a string passed to link generator. By default is `default` for `createModalLink()` method and `default-ajax` for `createModalLinkAjax()` method. See `hook_contact_tools_modal_link_options_alter()` for more information.

#### Examples

```php
$contact_tools = \Drupal::service('contact_tools');

// Link which open contact tools in modal without AJAX handler.
$feedback_in_modal = $contact_tools->reateModalLinkAjax('Write to use!', 'feedback');

// Link which open contact form in modal with AJAX submit handler.
$callback_link = $contact_tools->createModalLinkAjax('Call me', 'callback');

// This link pass query parameters to controller, that can be used for your needs.
// Also set modal width to 300 and additional class to link 'request-support-button'.
// By pass nid in service, you can access it in hook_form_alter() hooks by
// \Drupal::request()->query->get('service') and do whatever you want with it. F.e.
// set this value and hide form field from user, that service name will be send with
// form, but user don't need to fill and see it.
$link_options = [
  'query' => [
    'service' => $node->id(),
  ],
  'attributes' => [
    // use-ajax class will be added anyway. You don't need to worry about it.
    'class' => ['request-support-button'],
    'data-dialog-options' => [
      'width' => 300,
    ]
  ],
];
$request_support = $contact_tools->createModalLinkAjax('Call me', 'callback', $link_options);
```
----------------------------
# THE DOC BELOW IS NOT COMPLETED, BUT ACTUAL AT MOST

<a name="filter"></a>

## Filter

### modalLink

```html
[contact]{"type": "modalLink", "contact_form": "callback", "link_title": "Call me!", "link_options": {"attributes":{"class":["callback-link"]}}}[/contact]
```

### modalLinkAjax

```html
[contact]{"type": "modalLinkAjax", "contact_form": "callback", "link_title": "Call me!", "link_options": {"attributes":{"class":["callback-link"]}}}[/contact]
```

<a name="twig"></a>

## Twig

Module also provides Twig functions as well!

### contact_form_ajax()

Load contact form with ajax support.

#### Example 1

```twig
{{ contact_form('feedback') }}
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

## Hooks

@todo
If you interested in hook, see `contact_tools.api.php`.