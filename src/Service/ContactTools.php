<?php

namespace Drupal\contact_tools\Service;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Url;

/**
 * Main class for all snippets and helpers.
 */
final class ContactTools {

  /**
   * The contact message storage.
   *
   * @var \Drupal\Core\Entity\ContentEntityStorageInterface
   */
  protected ContentEntityStorageInterface $contactStorage;

  /**
   * The entity form builder.
   *
   * @var \Drupal\Core\Entity\EntityFormBuilderInterface
   */
  protected EntityFormBuilderInterface $entityFormBuilder;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected ModuleHandlerInterface $moduleHandler;

  /**
   * Constructs a new ContactTools object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder
   *   The entity form builder.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, EntityFormBuilderInterface $entity_form_builder, ModuleHandlerInterface $module_handler) {
    $this->contactStorage = $entity_type_manager->getStorage('contact_message');
    $this->entityFormBuilder = $entity_form_builder;
    $this->moduleHandler = $module_handler;
  }

  /**
   * Return modal link which load form in modal.
   *
   * @param string $link_title
   *   Title of the link.
   * @param string $contact_form
   *   The machine name of contact form needed to be loaded in modal.
   * @param array $link_options
   *   (optional) An array of options. Used to pass additional link options, for
   *   more information see Url::fromUri(). Modal API which used here to show
   *   form supports for modal API via data-dialog-options attribute. You can
   *   pass personal settings according to jQuery Dialog Widget. See
   *   http://api.jqueryui.com/dialog/ for details.
   *
   * @return array
   *   Renderable array with link.
   */
  public function createModalLink(string $link_title, string $contact_form, array $link_options = []): array {
    $link_options_merged = $this->mergeLinkOptions($this->getLinkOptionsDefault(), $link_options);
    $context = [
      'contact_form' => $contact_form,
      'link_title' => $link_title,
      'type' => 'modal_link',
    ];
    $this->modalLinkOptionsAlter($link_options_merged, $context);
    // Modal settings must be in json format.
    $link_options_merged['attributes']['data-dialog-options'] = Json::encode($link_options_merged['attributes']['data-dialog-options']);

    return [
      '#type' => 'link',
      '#title' => $link_title,
      '#url' => Url::fromRoute('entity.contact_form.canonical', ['contact_form' => $contact_form], []),
      '#options' => $link_options_merged,
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }

  /**
   * Return modal link which load form in modal with AJAX support.
   *
   * @param string $link_title
   *   Title of the link.
   * @param string $contact_form
   *   The machine name of contact form needed to be loaded in modal.
   * @param array $link_options
   *   (optional) An array of options. Used to pass additional link options, for
   *   more information see Url::fromUri(). Modal API which used here to show
   *   form supports for modal API via data-dialog-options attribute. You can
   *   pass personal settings according to jQuery Dialog Widget. See
   *   http://api.jqueryui.com/dialog/ for details.
   *
   * @return array
   *   Renderable array with link.
   */
  public function createModalLinkAjax(string $link_title, string $contact_form, array $link_options = []): array {
    $link_options_merged = $this->mergeLinkOptions($this->getLinkOptionsDefault(), $link_options);
    $context = [
      'contact_form' => $contact_form,
      'link_title' => $link_title,
      'type' => 'modal_link_ajax',
    ];
    $this->modalLinkOptionsAlter($link_options_merged, $context);
    // Modal settings must be in json format.
    $link_options_merged['attributes']['data-dialog-options'] = Json::encode($link_options_merged['attributes']['data-dialog-options']);

    return [
      '#type' => 'link',
      '#title' => $link_title,
      '#url' => Url::fromRoute('contact_tools.contact_form_ajax.page', ['contact_form' => $contact_form], []),
      '#options' => $link_options_merged,
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }

  /**
   * Return contact form renderable array.
   *
   * @param string $contact_form_id
   *   Machine name of contact form to be loaded.
   * @param array $form_state_additions
   *   (optional) An associative array used to build the current state of the
   *   form. Use this to pass additional information to the form, such as the
   *   langcode. Defaults to an empty array.
   *
   * @return array
   *   Render array with form.
   */
  public function getForm(string $contact_form_id = 'default_form', array $form_state_additions = []): array {
    $contact_message = $this->contactStorage->create([
      'contact_form' => $contact_form_id,
    ]);

    $form = $this->entityFormBuilder->getForm($contact_message, 'default', $form_state_additions);
    $form['#title'] = $contact_message->label();
    $form['#cache']['contexts'][] = 'user.permissions';
    return $form;
  }

  /**
   * Return contact form renderable array with AJAX support.
   *
   * @param string $contact_form_id
   *   Machine name of contact form to be loaded.
   * @param array $form_state_additions
   *   (optional) An associative array used to build the current state of the
   *   form. Use this to pass additional information to the form, such as the
   *   langcode. Defaults to an empty array.
   *
   * @return array
   *   Render array with form.
   */
  public function getFormAjax(string $contact_form_id = 'default_form', array $form_state_additions = []): array {
    $contact_message = $this->contactStorage->create([
      'contact_form' => $contact_form_id,
    ]);
    // Ajax is added by hook_form_alter(). Because here we can't change any of
    // actions of the form.
    $form_state_additional_default = [
      'contact_tools' => [
        'is_ajax' => TRUE,
      ],
    ];

    $form_state_additions = NestedArray::mergeDeepArray([
      $form_state_additions,
      $form_state_additional_default,
    ]);

    $form = $this->entityFormBuilder->getForm($contact_message, 'default', $form_state_additions);
    $form['#title'] = $contact_message->label();
    $form['#cache']['contexts'][] = 'user.permissions';
    return $form;
  }

  /**
   * Define hook_contact_tools_modal_link_options_alter().
   *
   * Allow modules to alter options for link via hook. Can be handful
   * when link is called via twig or filter, the most of data can be set via
   * hooks by the key. Can be used to set default settings for needed set of
   * forms.
   */
  protected function modalLinkOptionsAlter(array &$link_options, array $context = []): void {
    $this->moduleHandler->alter(
      'contact_tools_modal_link_options',
      $link_options['attributes']['data-dialog-options'],
      $context
    );
  }

  /**
   * Return default options for link.
   *
   * @return array
   *   An array with default options.
   */
  protected function getLinkOptionsDefault(): array {
    return [
      'attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => [
          'width' => 'auto',
          'dialogClass' => 'contact-tools-modal',
        ],
        'rel' => 'nofollow',
      ],
    ];
  }

  /**
   * Merges two arrays recursively, but replace existed values, not extend them.
   *
   * @param array $array1
   *   The first array to merge into.
   * @param array $array2
   *   The second array to merge.
   *
   * @return array
   *   A result array.
   */
  protected function arrayMergeRecursiveDistinct(array &$array1, array &$array2): array {
    $merged = $array1;

    foreach ($array2 as $key => &$value) {
      if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
        $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
      }
      else {
        $merged[$key] = $value;
      }
    }

    return $merged;
  }

  /**
   * Fix some sensitive values that can be overridden by distinct merge.
   *
   * @param array $array1
   *   The first array to merge into.
   * @param array $array2
   *   The second array to merge.
   *
   * @return array
   *   A result array
   */
  protected function mergeLinkOptions(array $array1, array $array2): array {
    $merged = $this->arrayMergeRecursiveDistinct($array1, $array2);

    if (!empty($merged['attributes']['class']) && !in_array('use-ajax', $merged['attributes']['class'])) {
      $merged['attributes']['class'][] = 'use-ajax';
    }
    return $merged;
  }

}
