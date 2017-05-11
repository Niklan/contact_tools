<?php

namespace Drupal\contact_tools;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Url;

/**
 * Main class for all snippets and helpers.
 */
class ContactTools {

  /**
   * @param       $link_title
   *   The title of the link.
   * @param       $contact_form
   *   The machine name of contact form needed to be loaded in modal.
   * @param array $url_options
   *   (optional) An array of options. Mainly used for pass GET parameters.
   *   See Url::fromUri() for details.
   * @param array $link_options
   *   (optional) An array of options. Here must be passed all settings for
   *   modal window. See Url::fromUri() for details.
   *
   * @return array
   *   Renderable array with link.
   */
  public static function createModalLink($link_title, $contact_form, $url_options = [], $link_options = []) {
    $link_options_defaults = [
      'attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => Json::encode([
          'width' => 'auto',
        ]),
        'rel' => 'nofollow',
      ],
    ];

    $link_options = array_merge($link_options_defaults, $link_options);

    return [
      '#type' => 'link',
      '#title' => $link_title,
      '#url' => Url::fromRoute('entity.contact_form.canonical', ['contact_form' => $contact_form], $url_options),
      '#options' => $link_options,
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }

  /**
   * @param       $link_title
   *   The title of the link.
   * @param       $contact_form
   *   The machine name of contact form needed to be loaded in modal.
   * @param array $url_options
   *   (optional) An array of options. Mainly used for pass GET parameters.
   *   See Url::fromUri() for details.
   * @param array $link_options
   *   (optional) An array of options. Here must be passed all settings for
   *   modal window. See Url::fromUri() for details.
   *
   * @return array
   *   Renderable array with link.
   */
  public static function createModalLinkAjax($link_title, $contact_form, $url_options = [], $link_options = []) {
    $link_options_defaults = [
      'attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => Json::encode([
          'width' => 'auto',
        ]),
        'rel' => 'nofollow',
      ],
    ];

    $link_options = array_merge($link_options_defaults, $link_options);

    return [
      '#type' => 'link',
      '#title' => $link_title,
      '#url' => Url::fromRoute('contact_tools.contact_form_ajax.page', ['contact_form' => $contact_form], $url_options),
      '#options' => $link_options,
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }

}
