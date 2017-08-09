<?php

namespace Drupal\contact_tools\Service;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\contact\Entity\ContactForm;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Main class for all snippets and helpers.
 */
class ContactTools {

  /**
   * @param       $link_title
   *   The title of the link.
   * @param       $contact_form
   *   The machine name of contact form needed to be loaded in modal.
   * @param array $link_options
   *   (optional) An array of options. Here must be passed all settings for
   *   modal window. See Url::fromUri() for details.
   * @param array $url_options
   *   (optional) An array of options. Mainly used for pass GET parameters.
   *   See Url::fromUri() for details
   *
   * @return array
   *   Renderable array with link.
   */
  public static function createModalLink($link_title, $contact_form, $link_options = [], $url_options = []) {
    $link_options_defaults = [
      'attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => [
          'width' => 'auto',
        ],
        'rel' => 'nofollow',
      ],
    ];

    $link_options_merged = array_merge_recursive($link_options_defaults, $link_options);
    // Modal settings must be in json format.
    $link_options_merged['attributes']['data-dialog-options'] = Json::encode($link_options_merged['attributes']['data-dialog-options']);

    return [
      '#type' => 'link',
      '#title' => $link_title,
      '#url' => Url::fromRoute('entity.contact_form.canonical', ['contact_form' => $contact_form], $url_options),
      '#options' => $link_options_merged,
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }

  /**
   * @param       $link_title
   *   The title of the link.
   * @param       $contact_form
   *   The machine name of contact form needed to be loaded in modal.
   * @param array $link_options
   *   (optional) An array of options. Here must be passed all settings for
   *   modal window. See Url::fromUri() for details.
   * @param array $url_options
   *   (optional) An array of options. Mainly used for pass GET parameters.
   *   See Url::fromUri() for details.
   *
   * @return array
   *   Renderable array with link.
   */
  public static function createModalLinkAjax($link_title, $contact_form, $link_options = [], $url_options = []) {
    $link_options_defaults = [
      'attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => [
          'width' => 'auto',
        ],
        'rel' => 'nofollow',
      ],
    ];

    $link_options_merged = array_merge_recursive($link_options_defaults, $link_options);
    // Modal settings must be in json format.
    $link_options_merged['attributes']['data-dialog-options'] = Json::encode($link_options_merged['attributes']['data-dialog-options']);

    return [
      '#type' => 'link',
      '#title' => $link_title,
      '#url' => Url::fromRoute('contact_tools.contact_form_ajax.page', ['contact_form' => $contact_form], $url_options),
      '#options' => $link_options_merged,
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }

  /**
   * @param string $contact_form_id
   *
   * @return array Renderable array with form.
   * Renderable array with form.
   */
  public static function getFormAjax($contact_form_id = 'default_form') {
    $contact_message = \Drupal::entityTypeManager()
      ->getStorage('contact_message')
      ->create([
        'contact_form' => $contact_form_id,
      ]);
    // Ajax is added by hook_form_alter(). Because here we can't change any of
    // actions of the form.
    $form_state_additional = [
      'contact_tools' => [
        'is_ajax' => TRUE,
      ],
    ];
    $form = \Drupal::service('entity.form_builder')->getForm($contact_message, 'default', $form_state_additional);
    $form['#title'] = $contact_message->label();
    $form['#cache']['contexts'][] = 'user.permissions';
    return $form;
  }

}
