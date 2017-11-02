<?php

/**
 * @file
 * Hook examples defined by this module.
 */

use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Implements hook_contact_tools_modal_link_options_alter().
 *
 * Allows you to alter link and url options for modal links.
 *
 * @param array $link_options
 *   An array of options for modal window.
 */
function hook_contact_tools_modal_link_options_alter(array &$link_options) {
  $link_options['attributes']['data-dialog-options']['width'] = 600;
  $link_options['attributes']['data-dialog-options']['dialogClass'] = 'my-special-form';
}

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
