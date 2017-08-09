<?php

/**
 * @file
 * Hook examples defined by this module.
 */

use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Implements hook_contact_tools_modal_link_options_alter().
 *
 * Allows you to alter link and url options for modal links. You can change them
 * globally or find needed by the key. By default modal has key 'default' and
 * ajax modal is 'default-ajax', you can pass your own keys to add special
 * behavior.
 */
function dummy_contact_tools_modal_link_options_alter(array &$link_options, array &$url_options, $key) {
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

/**
 * Implements hook_contact_tools_ajax_response_alter().
 *
 * Allows modules to alter AJAX response handled by the module. You can fully
 * alter, remove and add new commands to response.
 */
function contact_tools_contact_tools_ajax_response_alter(\Drupal\core\Ajax\AjaxResponse &$ajax_response, $form, Drupal\Core\Form\FormStateInterface $form_state) {
  if ($form_state->isSubmitted()) {
    $ajax_response->addCommand(new ReplaceCommand('#contact-form-' . $form['#build_id'], t('Thank you for your submission!')));
  }
}
