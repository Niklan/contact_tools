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
      'data-dialog-options' => [
        'width' => 'auto',
      ],
    ],
  ];
  ```
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

<a name="filter"></a>

## Filter

**I still think about this filters, maybe I will short them or remove completely. This is can cause form submissions problems when you call form this way, also this can be performance problem, and preview mode problems.**

If you have better solution, contact me or create an issue.

Filter dous all the same as Service but via simple text editor filters. Filter must be wrapper in `[contact][/contact]`. Inside this construction you must put valid JSON array with all information as you pass in service. They keys of array is the same as names of parameters for methods, and type is equal method name. All other is the same.

### Examples

```html
<!-- Modal link with AJAX form -->
[contact]{"type": "modalLinkAjax", "contact_form": "callback", "link_title": "Call me!", "link_options": {"attributes": {"class":["callback-link"]},"query": {"node": "Title of the node"}}}[/contact]

<!-- Render form with AJAX submit handler insde the text. This can affect your site performance and behaviour. -->
[contact]{"type": "getFormAjax", "contact_form": "callback"}[/contact]
```

This is bad solution for several reasons: 

1. Modal links will work perfectly, but the filter for them is very hard to read and maintane. Any symbol which not valid in json will broke it, and this is easy yo reproduce. 
2. Printing form like this can decrease site performance because of cache tags of the form. Second example is paragraphs and modules like this. F.e. you have paragraph with text and use this method, and if your paragraph field has setting to preview results while editing, the form will be rendered aswell. Problems is this form will be inside another form and submit handler for paragraph will broke and you can't edit it. 

I'am hardly not recommend using this approach, I want to find better solution and remove it.

<a name="twig"></a>

## Twig

Twig is awsome and used alot in Drupal theming. Module is providing Twig functions for contact forms aswell!

### Examples

```twig
{# Just contact form. #}
{{ contact_form('feedback') }}

{# Contact form with AJAX. #}
{{ contact_form_ajax('feedback') }}

{# Modal link with AJAX form. #}
{{ contact_modal_ajax('Write to us!', 'feedback') }}

{# Create modal link and pass some data with it #}
{% set link_options = {
  query: {
    service: node.nid
  }
} %}
{{ contact_modal_ajax('Write to us!', 'feedback', link_options) }}

{#
  /**
   * If you want to pass more parameters, f.e. query and attributes, you 
   * can't do this in twig, because Twig is not allow to create multidimensional
   * arrays. But there is soultion for it, you can pass most important options
   * like query, and also set the special key for it, then use hook and add other
   * parameters to it.
   *
   * @see hook_contact_tools_modal_link_options_alter().
   */
#}
{{ contact_modal_ajax('Write to us!', 'feedback', [], 'write_to_us') }}
```

## Hooks

There is several hooks that can be handful in some cases.

### hook_contact_tools_modal_link_options_alter()

```php
/**
 * Implements hook_contact_tools_modal_link_options_alter().
 *
 * Allows you to alter link and url options for modal links. You can change them
 * globally or find needed by the key. By default modal has key 'default' and
 * ajax modal is 'default-ajax', you can pass your own keys to add special
 * behavior.
 */
function hook_contact_tools_modal_link_options_alter(array &$link_options, $key) {
  switch ($key) {
    case 'default':
    case 'default-ajax':
      // Set width for all modals with contact form to 600px.
      $link_options['attributes']['data-dialog-options']['width'] = 600;
      // Add class to modal, which can be used to theme modal with different
      // styles on your needs.
      $link_options['attributes']['data-dialog-options']['dialogClass'] = 'my-special-form';
      break;
  }
}
```

### hook_contact_tools_ajax_response_alter() and hook_contact_tools_CONTACT_NAME_ajax_response_alter()

```php
/**
 * Implements hook_contact_tools_ajax_response_alter().
 *
 * Allows modules to alter AJAX response handled by the module. You can fully
 * alter, remove and add new commands to response.
 */
function hook_contact_tools_ajax_response_alter(\Drupal\core\Ajax\AjaxResponse &$ajax_response, $form, Drupal\Core\Form\FormStateInterface $form_state) {
  if ($form_state->isSubmitted()) {
    $ajax_response->addCommand(new ReplaceCommand('#contact-form-' . $form['#build_id'], t('Thank you for your submission!')));
  }
}
/**
 * Implements hook_contact_tools_CONTACT_NAME_ajax_response_alter().
 *
 * Allows modules to alter AJAX response handled by the module. You can fully
 * alter, remove and add new commands to response.
 *
 * This hook only apply for specified contact form name. You must pass only
 * machine name of contact form. F.e. is form has form_id
 * "contact_message_feedback_form" so form name here is "feedback". In other
 * words, this is bundle name of the contact_message entity.
 */
function hook_contact_tools_CONTACT_NAME_ajax_response_alter(\Drupal\core\Ajax\AjaxResponse &$ajax_response, $form, Drupal\Core\Form\FormStateInterface $form_state) {
  if ($form_state->isSubmitted()) {
    $ajax_response->addCommand(new ReplaceCommand('#contact-form-' . $form['#build_id'], t('Thank you for your submission!')));
  }
}
```
